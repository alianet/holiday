<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Ukraine;

use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;
use Umulmrum\Holiday\Provider\Religion\ChristianOrthodoxHolidaysTrait;

use function sprintf;

class Ukraine implements CompensatoryHolidayProviderInterface
{
    use ChristianHolidaysTrait, ChristianOrthodoxHolidaysTrait {
        ChristianOrthodoxHolidaysTrait::getGoodFriday insteadof ChristianHolidaysTrait;
        ChristianOrthodoxHolidaysTrait::calculateEasterSunday insteadof ChristianHolidaysTrait;
        ChristianOrthodoxHolidaysTrait::getEasterSunday insteadof ChristianHolidaysTrait;
        ChristianOrthodoxHolidaysTrait::getEasterSundayDate insteadof ChristianHolidaysTrait;
        ChristianOrthodoxHolidaysTrait::getEasterMonday insteadof ChristianHolidaysTrait;
        ChristianOrthodoxHolidaysTrait::getWhitSunday insteadof ChristianHolidaysTrait;
        ChristianHolidaysTrait::getChristmasDay insteadof ChristianOrthodoxHolidaysTrait;
        ChristianHolidaysTrait::getChristmasDay as getWesternChristmasDay;
        ChristianOrthodoxHolidaysTrait::getChristmasDay as getEasternChristmasDay;
    }
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $isMartialLaw = $year >= 2022;
        $isMartialLawAtBeginOfYear = $year >= 2023;
        $holidayType = $isMartialLaw ? HolidayType::OFFICIAL : HolidayType::OFFICIAL | HolidayType::DAY_OFF;
        $holidayTypeAtBeginOfYear = $isMartialLawAtBeginOfYear ? HolidayType::OFFICIAL : HolidayType::OFFICIAL | HolidayType::DAY_OFF;

        $holidays = new HolidayList();
        if (($year >= 1898 && $year <= 1929) || $year >= 1948) {
            $holidays->add($this->getNewYear($year, $holidayTypeAtBeginOfYear));
        }
        if ($year <= 2022) {
            $holidays->add($this->getEasternChristmasDay($year, $holidayTypeAtBeginOfYear));
        }
        if ($year >= 1966) {
            $holidays->add($this->getInternationalWomensDay($year, $holidayType));
        }
        if ($year >= 1991) {
            $holidays->add($this->getEasterSunday($year, $holidayType));
            $holidays->add($this->getWhitSunday($year, $holidayType));
        }
        if ($year >= 1918) {
            $holidays->add($this->getLaborDayUkraine($year, $holidayType));
        }
        if ($year >= 1928 && $year <= 2017) {
            $holidays->add($this->getOldLaborDayUkraine($year, $holidayType));
        }
        if ($year >= 2024) {
            $holidays->add($this->getDayOfRemembranceAndVictoryOverNazism($year, $holidayType));
        }
        if ($year >= 1945 && $year <= 2023) {
            $holidays->add($this->getDayOfVictoryOverNazism($year, $holidayType));
        }
        if ($year >= 1997) {
            $holidays->add($this->getConstitutionDay($year, $holidayType));
        }
        if ($year >= 2022) {
            $holidays->add($this->getStatehoodDay($year, $holidayType));
        }
        if ($year >= 1992) {
            $holidays->add($this->getIndependenceDay($year, $holidayType));
        }
        if ($year >= 2015) {
            $holidays->add($this->getDefendersOfUkraineDay($year, $holidayType));
        }
        if ($year >= 2017) {
            $holidays->add($this->getWesternChristmasDay($year, $year === 2018 ? HolidayType::OFFICIAL : $holidayType));
        }

        return $holidays;
    }

    protected function getLaborDayUkraine(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year <= 2017) {
            $name = HolidayName::DAY_OF_INTERNATIONAL_SOLIDARITY_OF_WORKERS;
        } else {
            $name = HolidayName::LABOR_DAY;
        }

        return Holiday::create($name, sprintf('%04d-05-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getOldLaborDayUkraine(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::LABOR_DAY, sprintf('%04d-05-02', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getDayOfRemembranceAndVictoryOverNazism(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::DAY_OF_REMEMBRANCE_AND_VICTORY_OVER_NAZISM, sprintf('%04d-05-08', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getDayOfVictoryOverNazism(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year >= 2016) {
            $name = HolidayName::DAY_OF_VICTORY_OVER_NAZISM;
        } else {
            $name = HolidayName::VICTORY_DAY;
        }

        return Holiday::create($name, sprintf('%04d-05-09', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getConstitutionDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::UKRAINE_CONSTITUTION_DAY, sprintf('%04d-06-28', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getStatehoodDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::UKRAINE_STATEHOOD_DAY, sprintf('%04d-07-15', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getIndependenceDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::UKRAINE_INDEPENDENCE_DAY, sprintf('%04d-08-24', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getDefendersOfUkraineDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year >= 2023) {
            $date = sprintf('%04d-10-01', $year);
        } else {
            $date = sprintf('%04d-10-14', $year);
        }

        return Holiday::create(HolidayName::DEFENDERS_OF_UKRAINE_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        if ($year <= 1994 || $year === 1998 || $year === 1999) {
            return [];
        }

        return [new CompensatoryDaysCalculator()];
    }
}
