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

class BritishColumbia extends Canada
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->replaceByNameAndDate($this->getRemembranceDay($year));
        $holidays->replaceByNameAndDate($this->getThanksgiving($year));
        $holidays->add($this->getVictoriaDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getBritishColumbiaDay($year));
        if ($year >= 2013) {
            $date = new DateTime(sprintf($year < 2019 ? 'Second Monday of %04d-02' : 'Third Monday of %04d-02', $year));
            $holidays->add(Holiday::createFromDateTime(HolidayName::FAMILY_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        if ($year >= 2023) {
            $holidays->replaceByNameAndDate($this->getNationalDayForTruthAndReconciliation($year, HolidayType::DAY_OFF));
        }

        return $holidays;
    }

    protected function getBritishColumbiaDay(int $year): Holiday
    {
        $date = new DateTime(sprintf('First Monday of %04d-08', $year));

        return Holiday::createFromDateTime(HolidayName::BRITISH_COLUMBIA_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }
}
