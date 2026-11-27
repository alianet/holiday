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

class Saskatchewan extends Canada
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->replaceByNameAndDate($this->getRemembranceDay($year));
        $holidays->replaceByNameAndDate($this->getThanksgiving($year));
        if ($year >= 2007) {
            $holidays->add($this->getFamilyDay($year));
        }
        $holidays->add($this->getVictoriaDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getSaskatchewanDay($year));

        return $holidays;
    }

    protected function getSaskatchewanDay(int $year): Holiday
    {
        $date = new DateTime(sprintf('First Monday of %04d-08', $year));

        return Holiday::createFromDateTime(HolidayName::SASKATCHEWAN_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }
}
