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

class NovaScotia extends Canada
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        if ($year >= 2015) {
            $holidays->add($this->getHeritageDay($year));
        }
        $holidays->add($this->getThanksgiving($year, HolidayType::PARTIAL_ONLY));

        return $holidays;
    }

    protected function getHeritageDay(int $year): Holiday
    {
        $date = new DateTime(sprintf('Third Monday of %04d-02', $year));

        return Holiday::createFromDateTime(HolidayName::NOVA_SCOTIA_HERITAGE_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }
}
