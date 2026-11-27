<?php

declare(strict_types=1);

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Sweden;

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

class Sweden implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function __construct(
        protected readonly bool $includeDeFactoHalfHolidays = true,
        protected readonly bool $includeDeFactoFullHolidays = true,
    ) {}

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($this->includeDeFactoHalfHolidays) {
            $holidays->add($this->getTwelfthNight($year, HolidayType::HALF_DAY_OFF | HolidayType::PARTIAL_ONLY));
        }
        $holidays->add($this->getEpiphany($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        $holidays->add($this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($this->includeDeFactoHalfHolidays) {
            $holidays->add($this->getWalpurgisNight($year, HolidayType::HALF_DAY_OFF | HolidayType::PARTIAL_ONLY));
        }
        $holidays->add($this->getLaborDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getAscension($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getNationalDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getWhitSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($this->includeDeFactoFullHolidays) {
            $holidays->add($this->getMidsummerEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        }
        $holidays->add($this->getMidsummersDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($this->includeDeFactoHalfHolidays) {
            $holidays->add($this->getSwedishAllSaintsEve($year, HolidayType::HALF_DAY_OFF | HolidayType::PARTIAL_ONLY));
        }
        $holidays->add($this->getSwedishAllSaintsDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($this->includeDeFactoFullHolidays) {
            $holidays->add($this->getChristmasEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        }
        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getSecondChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($this->includeDeFactoFullHolidays) {
            $holidays->add($this->getNewYearsEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));
        }

        return $holidays;
    }

    protected function getTwelfthNight(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::TWELFTH_NIGHT, sprintf('%04d-01-05', $year), HolidayType::RELIGIOUS | $additionalType);
    }

    protected function getWalpurgisNight(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::WALPURGIS_NIGHT, sprintf('%04d-04-30', $year), HolidayType::TRADITIONAL | $additionalType);
    }

    protected function getNationalDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::SWEDEN_NATIONAL_DAY, sprintf('%04d-06-06', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getMidsummerEve(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = new DateTimeImmutable(sprintf('%04d-06-19 this friday', $year));

        return Holiday::createFromDateTime(HolidayName::MIDSUMMER_EVE, $date, HolidayType::TRADITIONAL | $additionalType);
    }

    protected function getMidsummersDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = new DateTimeImmutable(sprintf('%04d-06-20 this saturday', $year));

        return Holiday::createFromDateTime(HolidayName::MIDSUMMERS_DAY, $date, HolidayType::TRADITIONAL | $additionalType);
    }

    protected function getSwedishAllSaintsEve(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = new DateTimeImmutable(sprintf('%04d-10-30 this friday', $year));

        return Holiday::createFromDateTime(HolidayName::ALL_SAINTS_EVE, $date, HolidayType::RELIGIOUS | $additionalType);
    }

    protected function getSwedishAllSaintsDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = new DateTimeImmutable(sprintf('%04d-10-31 this saturday', $year));

        return Holiday::createFromDateTime(HolidayName::ALL_SAINTS_DAY, $date, HolidayType::RELIGIOUS | $additionalType);
    }
}
