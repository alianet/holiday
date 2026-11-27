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

namespace Umulmrum\Holiday\Provider\Finland;

use DateInterval;
use DateTimeImmutable;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Finland implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getFinnishEpiphany($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        $holidays->add($this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getLaborDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getFinnishAscension($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getWhitSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getMidsummerEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        $holidays->add($this->getMidsummersDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getFinnishAllSaintsDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getIndependenceDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getChristmasEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getSecondChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));

        return $holidays;
    }

    protected function getFinnishEpiphany(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year < 1973 || $year > 1991) {
            return Holiday::create(HolidayName::EPIPHANY, sprintf('%04d-01-06', $year), HolidayType::RELIGIOUS | $additionalType);
        }

        $date = new DateTimeImmutable(sprintf('%04d-01-06 this saturday', $year));

        return Holiday::createFromDateTime(HolidayName::EPIPHANY, $date, HolidayType::RELIGIOUS | $additionalType);
    }

    protected function getFinnishAscension(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year < 1973 || $year > 1991) {
            return $this->getAscension($year, $additionalType);
        }

        $easterSunday = $this->getEasterSundayDate($year);

        // "Saturday before the traditional Thursday" --> 34 days after Easter Sunday instead of 39 days
        return Holiday::createFromDateTime(HolidayName::ASCENSION, $easterSunday->add(new DateInterval('P34D')), HolidayType::RELIGIOUS | $additionalType);
    }

    protected function getMidsummerEve(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year < 1955) {
            return Holiday::create(HolidayName::MIDSUMMERS_DAY, sprintf('%04d-06-23', $year), HolidayType::TRADITIONAL | $additionalType);
        }

        $date = new DateTimeImmutable(sprintf('%04d-06-19 this friday', $year));

        return Holiday::createFromDateTime(HolidayName::MIDSUMMER_EVE, $date, HolidayType::TRADITIONAL | $additionalType);
    }

    protected function getMidsummersDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year < 1955) {
            return Holiday::create(HolidayName::MIDSUMMERS_DAY, sprintf('%04d-06-24', $year), HolidayType::TRADITIONAL | $additionalType);
        }

        $date = new DateTimeImmutable(sprintf('%04d-06-20 this saturday', $year));

        return Holiday::createFromDateTime(HolidayName::MIDSUMMERS_DAY, $date, HolidayType::TRADITIONAL | $additionalType);
    }

    protected function getFinnishAllSaintsDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year < 1955) {
            return Holiday::create(HolidayName::ALL_SAINTS_DAY, sprintf('%04d-11-01', $year), HolidayType::RELIGIOUS | $additionalType);
        }

        $date = new DateTimeImmutable(sprintf('%04d-10-31 this saturday', $year));

        return Holiday::createFromDateTime(HolidayName::ALL_SAINTS_DAY, $date, HolidayType::RELIGIOUS | $additionalType);
    }

    protected function getIndependenceDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::FINLAND_INDEPENDENCE_DAY, sprintf('%04d-12-06', $year), HolidayType::OFFICIAL | $additionalType);
    }
}
