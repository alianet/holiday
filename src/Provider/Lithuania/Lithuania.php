<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Lithuania;

use DateTimeImmutable;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function array_filter;
use function sprintf;

class Lithuania implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        return new HolidayList(array_filter([
            $this->getNewYear($year, HolidayType::DAY_OFF),
            $this->getDayOfRestorationOfTheState($year, HolidayType::DAY_OFF),
            $this->getDayOfRestorationOfIndependence($year, HolidayType::DAY_OFF),
            $this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $this->getLaborDay($year, HolidayType::DAY_OFF),
            $this->getMothersDay($year, HolidayType::DAY_OFF),
            $this->getFathersDay($year, HolidayType::DAY_OFF),
            $this->getMidsummersDay($year, HolidayType::DAY_OFF),
            $this->getStatehoodDay($year, HolidayType::DAY_OFF),
            $this->getAssumptionDay($year, HolidayType::DAY_OFF),
            $this->getAllSaintsDay($year, HolidayType::DAY_OFF),
            $year >= 2020 ? $this->getAllSoulsDay($year, HolidayType::DAY_OFF) : null,
            $this->getChristmasEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY),
            $this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $this->getSecondChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
        ]));
    }

    protected function getDayOfRestorationOfTheState(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::DAY_OF_RESTORATION_OF_THE_STATE_OF_LITHUANIA, sprintf('%04d-02-16', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getDayOfRestorationOfIndependence(int $year, int $additionalType = HolidayType::OTHER): ?Holiday
    {
        return $year >= 1990
            ? Holiday::create(HolidayName::DAY_OF_RESTORATION_OF_INDEPENDENCE_OF_LITHUANIA, sprintf('%04d-03-11', $year), HolidayType::OFFICIAL | $additionalType)
            : null;
    }

    protected function getMidsummersDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::MIDSUMMERS_DAY, sprintf('%04d-06-24', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getMothersDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::MOTHERS_DAY,
            new DateTimeImmutable(sprintf('First Sunday of %04d-05', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function getFathersDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::FATHERS_DAY,
            new DateTimeImmutable(sprintf('First Sunday of %04d-06', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function getStatehoodDay(int $year, int $additionalType = HolidayType::OTHER): ?Holiday
    {
        return $year >= 1991
            ? Holiday::create(HolidayName::LITHUANIA_STATEHOOD_DAY, sprintf('%04d-07-06', $year), HolidayType::OFFICIAL | $additionalType)
            : null;
    }
}
