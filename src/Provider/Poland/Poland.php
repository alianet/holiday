<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Poland;

use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Poland implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($year >= 2011 || ($year >= 1925 && $year <= 1960)) {
            $holidays->add($this->getEpiphany($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        $holidays->add($this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getMayDay($year, HolidayType::DAY_OFF));
        if ($year >= 1990 || ($year >= 1919 && $year <= 1946)) {
            $holidays->add($this->getConstitutionDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1945 && $year <= 1950) {
            $holidays->add($this->getVictoryDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        if ($year >= 1951 && $year <= 2014) {
            $holidays->add($this->getVictoryDay($year, HolidayType::OFFICIAL));
        }
        if ($year >= 2015) {
            $holidays->add($this->getVictoryDay($year, HolidayType::OFFICIAL));
        }
        $holidays->add($this->getWhitSunday($year, HolidayType::DAY_OFF));
        $holidays->add($this->getCorpusChristi($year, HolidayType::DAY_OFF));
        $holidays->add($this->getAssumptionDay($year, HolidayType::DAY_OFF));
        if ($year >= 1992 || ($year >= 1923 && $year <= 1946)) {
            $holidays->add($this->getPolishArmedForcesDay($year, HolidayType::DAY_OFF));
        }
        $holidays->add($this->getAllSaintsDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getAllSoulsDay($year, HolidayType::NO_SCHOOL));
        if ($year >= 1989 || ($year >= 1937 && $year <= 1946)) {
            $holidays->add($this->getIndependenceDay($year, HolidayType::NO_SCHOOL | HolidayType::DAY_OFF));
        }
        $holidays->add($this->getChristmasDay($year, HolidayType::NO_SCHOOL));
        if ($year >= 2025) {
            $holidays->add($this->getChristmasEve($year, HolidayType::DAY_OFF | HolidayType::NO_SCHOOL));
        }
        $holidays->add($this->getSecondChristmasDay($year, HolidayType::NO_SCHOOL));

        return $holidays;
    }

    private function getMayDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::MAY_DAY, sprintf('%04d-05-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getConstitutionDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::CONSTITUTION_DAY, sprintf('%04d-05-03', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getVictoryDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = $year >= 2015 ? sprintf('%04d-05-08', $year) : sprintf('%04d-05-09', $year);

        return Holiday::create(HolidayName::VICTORY_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getPolishArmedForcesDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::POLISH_ARMED_FORCES_DAY, sprintf('%04d-08-15', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getIndependenceDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::INDEPENDENCE_DAY, sprintf('%04d-11-11', $year), HolidayType::OFFICIAL | $additionalType);
    }
}
