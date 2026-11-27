<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Provider\Jersey;

use DateTimeImmutable;
use InvalidArgumentException;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

/** Jersey only; published rules and audit limits are documented with the reference data. */
final class Jersey implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);
        if ($year < 2020 || $year > 2027) {
            throw new InvalidArgumentException('Crown dependency calendars support 2020–2027 only.');
        }
        $holidays = new HolidayList();
        $this->addObserved($holidays, 'new_year', "{$year}-01-01");
        $holidays->add($this->getGoodFriday($year, HolidayType::BANK | HolidayType::TRADITIONAL));
        $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::BANK));
        $may = $this->date("first Monday of {$year}-05");
        $this->add($holidays, 'may_day', $may);
        $this->add($holidays, 'spring_bank_holiday', $year === 2022 ? '2022-06-02' : $this->date("last Monday of {$year}-05"));
        if ($year === 2022) {
            $this->add($holidays, 'platinum_jubilee_bank_holiday', '2022-06-03');
            $this->add($holidays, 'state_funeral_of_queen_elizabeth', '2022-09-19');
        }
        if ($year === 2023) {
            $this->add($holidays, 'coronation_of_charles_and_camilla', '2023-05-08');
        }
        $this->add($holidays, 'summer_bank_holiday', $this->date("last Monday of {$year}-08"));
        $this->addObserved($holidays, 'christmas_day', "{$year}-12-25", true);
        $this->addObserved($holidays, 'boxing_day', "{$year}-12-26");
        $date = "{$year}-05-09";
        $type = HolidayType::TRADITIONAL;
        if ((new DateTimeImmutable($date))->format('N') !== '7') {
            $type |= HolidayType::OFFICIAL | HolidayType::BANK;
        }
        $holidays->add(Holiday::create('liberation_day', $date, $type));
        if ($year === 2020) {
            $this->add($holidays, HolidayName::VE_DAY_75, '2020-05-08');
        }
        if ($year === 2021) {
            $this->add($holidays, HolidayName::CODE_OF_1771_ANNIVERSARY, '2021-09-27');
        }
        if ($year === 2024) {
            $this->add($holidays, HolidayName::ROYAL_VISIT, '2024-07-15');
        }

        return $holidays;
    }

    private function date(string $expression): string
    {
        return (new DateTimeImmutable($expression))->format('Y-m-d');
    }

    private function add(HolidayList $holidays, string $name, string $date): void
    {
        $holidays->add(Holiday::create($name, $date, HolidayType::OFFICIAL | HolidayType::BANK));
    }

    private function addObserved(HolidayList $holidays, string $name, string $date, bool $customary = false): void
    {
        $day = new DateTimeImmutable($date);
        $weekend = (int) $day->format('N') > 5;
        $type = HolidayType::BANK | ($customary || $weekend ? HolidayType::TRADITIONAL : HolidayType::OFFICIAL);
        $holidays->add(Holiday::create($name, $date, $type));
        if ($weekend) {
            $observed = $day->modify('next Monday');
            if (($name === 'boxing_day' || $name === 'christmas_day') && $day->format('N') === '7') {
                $observed = $observed->modify('+1 day');
            }
            $holidays->add(Holiday::create($name . HolidayName::SUFFIX_COMPENSATORY, $observed->format('Y-m-d'), HolidayType::OFFICIAL | HolidayType::BANK | HolidayType::COMPENSATORY));
        }
    }
}
