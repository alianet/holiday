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

namespace Umulmrum\Holiday\Provider\Austria;

use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;

use function sprintf;

class Salzburg extends Austria
{
    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->add($this->getSaintRupertsDay($year, HolidayType::OFFICIAL | HolidayType::NO_SCHOOL));

        return $holidays;
    }

    private function getSaintRupertsDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::SAINT_RUPERTS_DAY, sprintf('%04d-09-24', $year), HolidayType::RELIGIOUS | $additionalType);
    }
}
