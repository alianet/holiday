<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Australia;

use DateTimeImmutable;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class NewSouthWales extends Australia
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->add($this->getEasterSaturday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getBankHoliday($year, HolidayType::BANK));
        $holidays->add($this->getLaborDayNewSouthWales($year, HolidayType::DAY_OFF));

        return $holidays;
    }

    protected function getBankHoliday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::BANK_HOLIDAY,
            new DateTimeImmutable(sprintf('First Monday of %04d-08', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function getLaborDayNewSouthWales(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::LABOR_DAY,
            new DateTimeImmutable(sprintf('First Monday of %04d-10', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }
}
