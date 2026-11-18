<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Estonia;

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

class Estonia implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        return new HolidayList(array_filter([
            $this->getNewYear($year, HolidayType::DAY_OFF),
            $this->getIndependenceDay($year, HolidayType::DAY_OFF),
            $this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY),
            $this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $this->getSpringDay($year, HolidayType::DAY_OFF),
            $this->getWhitSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $this->getVictoryDay($year, HolidayType::DAY_OFF),
            $this->getMidsummersDay($year, HolidayType::DAY_OFF),
            $this->getIndependenceRestorationDay($year, HolidayType::DAY_OFF),
            $this->getChristmasEve($year, HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY),
            $this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $this->getSecondChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
        ]));
    }

    protected function getMidsummersDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::MIDSUMMERS_DAY, sprintf('%04d-06-24', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getIndependenceDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::ESTONIA_INDEPENDENCE_DAY, sprintf('%04d-02-24', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getSpringDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::SPRING_DAY, sprintf('%04d-05-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getVictoryDay(int $year, int $additionalType = HolidayType::OTHER): ?Holiday
    {
        return Holiday::create(HolidayName::ESTONIA_VICTORY_DAY, sprintf('%04d-06-23', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getIndependenceRestorationDay(int $year, int $additionalType = HolidayType::OTHER): ?Holiday
    {
        return $year >= 1998
            ? Holiday::create(HolidayName::INDEPENDENCE_RESTORATION_DAY, sprintf('%04d-08-20', $year), HolidayType::OFFICIAL | $additionalType)
            : null;
    }
}
