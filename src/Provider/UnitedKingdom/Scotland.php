<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\UnitedKingdom;

use DateTime;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Scotland extends UnitedKingdom
{
    use ChristianHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year));
        $holidays->add($this->getNewYearHoliday($year));
        if ($year === 1968) {
            $holidays->add($this->getSterlingCrisis());
        }
        $holidays->add($this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($year === 2011) {
            $holidays->add($this->getWeddingOfWilliamAndCatherine());
        }
        $holidays->add($this->getEarlyMayDay($year));
        if ($year === 2023) {
            $holidays->add($this->getCoronationOfCharlesAndCamilla());
        }
        if ($year <= 1967) {
            $holidays->add($this->getWhitMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        $holidays->addAll($this->getSpringBankHolidays($year));
        if ($year === 1977) {
            $holidays->add($this->getSilverJubileeBankHoliday());
        }
        if ($year === 1981) {
            $holidays->add($this->getWeddingOfCharlesAndDiana());
        }
        $holidays->add($this->getSummerBankHoliday($year));
        if ($year === 2026) {
            $holidays->add(Holiday::create(HolidayName::WORLD_CUP_BANK_HOLIDAY, '2026-06-15', HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        if ($year === 2022) {
            $holidays->add($this->getStateFuneralOfQueenElizabeth());
        }
        if ($year === 1973) {
            $holidays->add($this->getWeddingOfAnneAndMark());
        }
        if ($year >= 2007) {
            $holidays->add($this->getSaintAndrewsDay($year));
        }
        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getBoxingDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($year === 1999) {
            $holidays->add($this->getYear2kCelebration());
        }

        return $holidays;
    }

    private function getNewYear(int $year): Holiday
    {
        return Holiday::create(HolidayName::NEW_YEAR, sprintf('%04d-01-01', $year), HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }

    private function getNewYearHoliday(int $year): Holiday
    {
        return Holiday::create(HolidayName::NEW_YEAR_HOLIDAY, sprintf('%04d-01-02', $year), HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }

    private function getSummerBankHoliday(int $year): Holiday
    {
        $date = (new DateTime(sprintf('First Monday of %04d-08', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::SUMMER_BANK_HOLIDAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }

    private function getSaintAndrewsDay(int $year): Holiday
    {
        return Holiday::create(HolidayName::SAINT_ANDREWS_DAY, sprintf('%04d-11-30', $year), HolidayType::OFFICIAL | HolidayType::BANK);
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        return [
            ...parent::getCompensatoryDaysCalculators($year),
            new CompensatoryDaysCalculator(
                [
                    HolidayName::NEW_YEAR_HOLIDAY,
                    HolidayName::SAINT_ANDREWS_DAY,
                ]
            ),
        ];
    }
}
