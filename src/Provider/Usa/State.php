<?php

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Usa;

use DateTimeImmutable;
use InvalidArgumentException;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculatorInterface;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;
use Umulmrum\Holiday\Provider\Usa\Internal\StateCompensatoryDaysCalculator;

/**
 * A complete state public calendar, independent of the federal Usa provider.
 * Rules adapted from python-holidays 0.109 (MIT); see Internal/LICENSE.python-holidays.
 */
abstract class State implements CompensatoryHolidayProviderInterface
{
    use CommonHolidaysTrait;
    use UsaHolidaysTrait;
    use ChristianHolidaysTrait;

    /** @var bool */
    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = true;

    /** @var bool */
    protected const INCLUDE_WASHINGTONS_BIRTHDAY = true;

    /** @var bool */
    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = true;

    /** @var bool */
    protected const INCLUDE_COLUMBUS_DAY = false;

    /** @var bool */
    protected const OBSERVE_SATURDAYS = true;

    protected int $year;
    private bool $includeObserved;
    private HolidayList $holidays;
    private string $observedRule = 'standard';

    /** @var array<string, string> */
    private array $namesByDate = [];

    final public function calculateHolidaysForYear(int $year): HolidayList
    {
        return $this->calculate($year, false);
    }

    /** @return CompensatoryDaysCalculatorInterface[] */
    final public function getCompensatoryDaysCalculators(int $year): array
    {
        Year::validate($year);

        return [new StateCompensatoryDaysCalculator(fn (int $year): HolidayList => $this->calculate($year, true))];
    }

    abstract protected function populateStateHolidays(): void;

    private function calculate(int $year, bool $includeObserved): HolidayList
    {
        Year::validate($year);
        // Isolate each calculation so provider instances can be reused for different years.
        $calendar = clone $this;
        $calendar->year = $year;
        $calendar->includeObserved = $includeObserved;
        $calendar->holidays = new HolidayList();
        $calendar->namesByDate = [];
        if ($year < 1777) {
            return $calendar->holidays;
        }
        $calendar->publicHolidays();
        $calendar->subdivisionHolidays();
        $calendar->populateStateHolidays();
        $result = new HolidayList();
        foreach ($calendar->holidays as $holiday) {
            if (str_starts_with($holiday->getSimpleDate(), sprintf('%04d-', $year))) {
                $result->add($holiday);
            }
        }

        return $result;
    }

    final protected function fixed(int $month, int $day, ?int $year = null): DateTimeImmutable
    {
        return new DateTimeImmutable(sprintf('%04d-%02d-%02d', $year ?? $this->year, $month, $day));
    }

    final protected function weekday(int $month, string $weekday, string $ordinal, int $offset = 0): DateTimeImmutable
    {
        return (new DateTimeImmutable(sprintf('%s %s of %04d-%02d', $ordinal, $weekday, $this->year, $month)))->modify(sprintf('%+d days', $offset));
    }

    /** Register a shared holiday using the state calendar's classification. */
    final protected function addHoliday(Holiday $holiday, ?string $name = null): DateTimeImmutable
    {
        return $this->add($name ?? $holiday->getName(), $holiday->getDate());
    }

    final protected function add(string $name, DateTimeImmutable $date): DateTimeImmutable
    {
        $this->namesByDate[$date->format('Y-m-d')] = $name;
        $this->holidays->add(Holiday::create($name, $date->format('Y-m-d'), HolidayType::OFFICIAL | HolidayType::PARTIAL_ONLY));

        return $date;
    }

    final protected function observe(DateTimeImmutable $date, ?string $name = null, ?string $rule = null, bool $showLabel = true): void
    {
        if (!$this->includeObserved || $this->year < 1953) {
            return;
        }
        $name ??= $this->namesByDate[$date->format('Y-m-d')];
        $weekday = (int) $date->format('N');
        $offset = match ($rule ?? $this->observedRule) {
            'standard' => match ($weekday) {
                6 => -1, 7 => 1, default => 0
            },
            'sunday' => $weekday === 7 ? 1 : 0,
            'christmasEve' => match ($weekday) {
                5, 6 => -1, 7 => -2, default => 0
            },
            'boxingDay' => match ($weekday) {
                1, 7 => 1, 6 => 2, default => 0
            },
            'washington' => match ($weekday) {
                1, 4 => 1, 2, 3, 5 => -1, 6, 7 => -2
            },
            default => throw new InvalidArgumentException('Unknown observed-day rule.'),
        };
        if ($offset !== 0 && $date->modify(sprintf('%+d days', $offset))->format('Y') === sprintf('%04d', $this->year)) {
            $this->holidays->add(Holiday::create(
                $name . ($showLabel ? HolidayName::SUFFIX_COMPENSATORY : ''),
                $date->modify(sprintf('%+d days', $offset))->format('Y-m-d'),
                HolidayType::OFFICIAL | HolidayType::PARTIAL_ONLY | HolidayType::COMPENSATORY,
            ));
        }
    }

    final protected function christmasEve(): void
    {
        $name = HolidayName::CHRISTMAS_EVE;
        $this->observe($this->addHoliday($this->getChristmasEve($this->year)), $name, 'christmasEve', true);
    }

    private function common(): void
    {
        $this->observedRule = ((($this->year >= 1966) && static::OBSERVE_SATURDAYS) ? 'standard' : 'sunday');
        if ($this->year >= 1871) {
            $name = HolidayName::NEW_YEAR;
            $this->observe($this->addHoliday($this->getNewYear($this->year)), null, null, true);
            if ($this->year >= 1967 && $this->year < 9999) {
                $this->observe($this->fixed(1, 1, $this->year + 1), $name, null, true);
            }
        }
        if ($this->year >= 1971) {
            $this->addHoliday($this->getMemorialDay($this->year));
        }
        if ($this->year >= 2021) {
            $this->observe($this->addHoliday($this->getJuneteenth($this->year)), null, null, true);
        }
        if ($this->year >= 1870) {
            $this->observe($this->addHoliday($this->getIndependenceDay($this->year)), null, null, true);
        }
        if ($this->year >= 1894) {
            $this->addHoliday($this->getLaborDayUsa($this->year));
        }
        if ($this->year >= 1862) {
            $thanksgiving_day_dates = [1862 => [4, 10], 1865 => [12, 7], 1869 => [11, 18], 1939 => [11, 23], 1940 => [11, 21], 1941 => [11, 20]];
            $name = HolidayName::THANKSGIVING_DAY;
            if ($dt = ($thanksgiving_day_dates[$this->year] ?? null)) {
                $this->add($name, $this->fixed($dt[0], $dt[1]));
            } else {
                if ($this->year >= 1942) {
                    $this->addHoliday($this->getThanksgivingDay($this->year));
                } else {
                    $this->add($name, $this->weekday(11, 'Thursday', 'last', 0));
                }
            }
        }
        if ($this->year >= 1870) {
            $this->observe($this->addHoliday($this->getChristmasDay($this->year)), null, null, true);
        }
    }

    private function publicHolidays(): void
    {
        $this->common();
        if (1888 <= $this->year && $this->year <= 1970) {
            $this->add(HolidayName::MEMORIAL_DAY, $this->fixed(5, 30));
        }
    }

    private function subdivisionHolidays(): void
    {
        if (($this->year >= 1986) && static::INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY) {
            $this->addHoliday($this->getMartinLutherKingJrDay($this->year));
        }
        if (($this->year >= 1879) && static::INCLUDE_WASHINGTONS_BIRTHDAY) {
            $name = HolidayName::WASHINGTONS_BIRTHDAY;
            if ($this->year >= 1971) {
                if (static::WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY) {
                    $this->addHoliday($this->getWashingtonsBirthday($this->year));
                }
            } else {
                $this->addHoliday($this->getWashingtonsBirthday($this->year));
            }
        }
        if ($this->year >= 1937 && static::INCLUDE_COLUMBUS_DAY) {
            $name = HolidayName::COLUMBUS_DAY;
            if ($this->year >= 1971) {
                $this->addHoliday($this->getColumbusDay($this->year));
            } else {
                $this->addHoliday($this->getColumbusDay($this->year));
            }
        }
        if ($this->year >= 1938) {
            $name = (($this->year >= 1954) ? HolidayName::VETERANS_DAY : HolidayName::ARMISTICE_DAY);
            if (1971 <= $this->year && $this->year <= 1977) {
                $this->add($name, $this->weekday(10, 'Monday', 'fourth', 0));
            } else {
                $this->observe($this->add($name, $this->fixed(11, 11)), null, null, true);
            }
        }
    }
}
