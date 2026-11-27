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

namespace Umulmrum\Holiday\Provider\Canada;

use DateTime;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Manitoba extends Canada
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        if ($year >= 2008) {
            $holidays->add($this->getLouisRielDay($year));
        }
        $holidays->add($this->getVictoriaDay($year, HolidayType::DAY_OFF));
        $holidays->removeByName(HolidayName::REMEMBRANCE_DAY);

        return $holidays;
    }

    protected function getLouisRielDay(int $year): Holiday
    {
        $date = new DateTime(sprintf('Third Monday of %04d-02', $year));

        return Holiday::createFromDateTime(HolidayName::LOUIS_RIEL_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }
}
