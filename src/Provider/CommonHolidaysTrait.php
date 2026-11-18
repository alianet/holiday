<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider;

use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;

use function sprintf;

trait CommonHolidaysTrait
{
    private function getNewYear(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::NEW_YEAR, sprintf('%04d-01-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getInternationalWomensDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::INTERNATIONAL_WOMENS_DAY, sprintf('%04d-03-08', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getInternationalChildrensDay(int $year, string $month, string $day, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::INTERNATIONAL_CHILDRENS_DAY, sprintf("%04d-{$month}-{$day}", $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getLaborDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::LABOR_DAY, sprintf('%04d-05-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getVictoryInEuropeDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::VICTORY_IN_EUROPE_DAY, sprintf('%04d-05-08', $year), HolidayType::OTHER | $additionalType);
    }

    private function getNewYearsEve(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::NEW_YEARS_EVE, sprintf('%04d-12-31', $year), HolidayType::OFFICIAL | $additionalType);
    }
}
