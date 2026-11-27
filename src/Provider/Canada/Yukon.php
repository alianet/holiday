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

namespace Umulmrum\Holiday\Provider\Canada;

use DateTime;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Yukon extends Canada
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->add($this->getVictoriaDay($year, HolidayType::DAY_OFF));
        if ($year >= 2017) {
            $holidays->add($this->getNationalIndigenousPeopleDay($year));
        }
        $holidays->add($this->getDiscoveryDay($year));
        if ($year >= 2023) {
            $holidays->replaceByNameAndDate($this->getNationalDayForTruthAndReconciliation($year, HolidayType::DAY_OFF));
        }
        $holidays->add($this->getBoxingDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::PARTIAL_ONLY));

        return $holidays;
    }

    protected function getDiscoveryDay(int $year): Holiday
    {
        $date = new DateTime(sprintf('Third Monday of %04d-08', $year));

        return Holiday::createFromDateTime(HolidayName::DISCOVERY_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }
}
