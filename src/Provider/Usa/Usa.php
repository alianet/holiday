<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Usa;

use DateTime;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Constant\Weekday;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Usa implements CompensatoryHolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;
    use UsaHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::DAY_OFF));
        if ($year >= 1986) {
            $holidays->add($this->getMartinLutherKingJrDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1879) {
            $holidays->add($this->getWashingtonsBirthday($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1868) {
            $holidays->add($this->getMemorialDay($year, HolidayType::DAY_OFF));
        }
        $this->addIndependenceDay($holidays, $year);
        if ($year >= 1894) {
            $holidays->add($this->getLaborDayUsa($year, HolidayType::DAY_OFF));
        }
        if (1892 === $year || $year >= 1968) {
            $holidays->add($this->getColumbusDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1938 && $year <= 1953) {
            $holidays->add($this->getArmisticeDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1954) {
            $this->addVeteransDay($holidays, $year);
        }
        if ($year >= 1885) {
            $holidays->add($this->getThanksgivingDay($year, HolidayType::DAY_OFF));
        }
        $this->addChristmasDay($holidays, $year);

        return $holidays;
    }

    private function addIndependenceDay(HolidayList $holidays, int $year): void
    {
        $holiday = Holiday::create(HolidayName::INDEPENDENCE_DAY, sprintf('%04d-07-04', $year), HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        $holidays->add($holiday);
    }

    private function addVeteransDay(HolidayList $holidays, int $year): void
    {
        if ($year >= 1971 && $year <= 1977) {
            $date = (new DateTime(sprintf('Fourth Monday of %04d-10', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } else {
            $date = sprintf('%04d-11-11', $year);
        }

        $holiday = Holiday::create(HolidayName::VETERANS_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        $holidays->add($holiday);
    }

    private function addChristmasDay(HolidayList $holidays, int $year): void
    {
        $holiday = $this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        $holidays->add($holiday);
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        $holidayNames = [
            HolidayName::NEW_YEAR,
            HolidayName::INDEPENDENCE_DAY,
            HolidayName::CHRISTMAS_DAY,
        ];
        if ($year >= 1978) {
            $holidayNames[] = HolidayName::VETERANS_DAY;
        }

        return [
            new CompensatoryDaysCalculator(
                $holidayNames,
                weekDaysToStepBackward: [Weekday::SATURDAY],
                weekDaysToStepForward: [Weekday::SUNDAY],
            ),
        ];
    }
}
