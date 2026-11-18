<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\CzechRepublic;

use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class CzechRepublic implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($year >= 2000) {
            $holidays->add($this->getRestorationDayOfTheIndependentCzechState($year, HolidayType::DAY_OFF));
        }
        if ($year >= 2016) {
            $holidays->add($this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        if ($year >= 1951) {
            $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
            $holidays->add($this->getLaborDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        if ($year >= 1952) {
            $holidays->add($this->getVictoryDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1990) {
            $holidays->add($this->getSaintsCyrilAndMethodiusDay($year, HolidayType::DAY_OFF));
            $holidays->add($this->getJanHusDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 2000) {
            $holidays->add($this->getStatehoodDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1988) {
            $holidays->add($this->getIndependentCzechoslovakStateDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 2000) {
            $holidays->add($this->getStruggleForFreedomDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1990) {
            $holidays->add($this->getChristmasEve($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        if ($year >= 1951) {
            $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
            $holidays->add($this->getSecondChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }

        return $holidays;
    }

    protected function getRestorationDayOfTheIndependentCzechState(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::RESTORATION_DAY_OF_THE_INDEPENDENT_CZECH_STATE, sprintf('%04d-01-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getVictoryDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        // The change to 8 May took effect on 31 May 1991.
        $date = sprintf($year >= 1992 ? '%04d-05-08' : '%04d-05-09', $year);

        return Holiday::create(HolidayName::VICTORY_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    protected function getSaintsCyrilAndMethodiusDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::SAINTS_CYRIL_AND_METHODIUS_DAY, sprintf('%04d-07-05', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getJanHusDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::JAN_HUS_DAY, sprintf('%04d-07-06', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getStatehoodDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::STATEHOOD_DAY, sprintf('%04d-09-28', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getIndependentCzechoslovakStateDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::INDEPENDENT_CZECHOSLOVAK_STATE_DAY, sprintf('%04d-10-28', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getStruggleForFreedomDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::STRUGGLE_FOR_FREEDOM_DAY, sprintf('%04d-11-17', $year), HolidayType::OFFICIAL | $additionalType);
    }
}
