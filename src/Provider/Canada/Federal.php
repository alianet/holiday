<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Provider\Canada;

use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

/** General holidays under the Canada Labour Code, rather than public-service agreements. */
class Federal extends Canada
{
    use ChristianHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->add($this->getVictoriaDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getBoxingDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->replaceByNameAndDate($this->getThanksgiving($year));
        $holidays->replaceByNameAndDate($this->getRemembranceDay($year));
        if ($year >= 2021) {
            $holidays->replaceByNameAndDate($this->getNationalDayForTruthAndReconciliation($year));
        }
        // A public-service day of mourning is not a Labour Code general holiday.
        $holidays->removeByName(HolidayName::NATIONAL_DAY_OF_MOURNING);

        return $holidays;
    }
}
