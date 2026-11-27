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

namespace Umulmrum\Holiday\Provider\Australia;

use DateTimeImmutable;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class SouthAustralia extends Australia
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        if ($year >= 1973) {
            $holidays->add($this->getAdelaideCupDay($year, HolidayType::DAY_OFF));
        }
        $holidays->add($this->getEasterSaturday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getLaborDaySouthAustralia($year, HolidayType::DAY_OFF));
        $holidays->add($this->getChristmasEve($year, HolidayType::OFFICIAL | HolidayType::HALF_DAY_OFF));
        if ($year >= 1910) {
            $this->addProclamationDay($holidays, $year, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        }
        $holidays->add($this->getNewYearsEve($year, HolidayType::OFFICIAL | HolidayType::HALF_DAY_OFF));

        return $holidays;
    }

    protected function getAdelaideCupDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year <= 2005) {
            $date = new DateTimeImmutable(sprintf('Third Monday of %04d-05', $year));
        } else {
            $date = new DateTimeImmutable(sprintf('Second Monday of %04d-03', $year));
        }

        return Holiday::createFromDateTime(
            HolidayName::ADELAIDE_CUP_DAY,
            $date,
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function getLaborDaySouthAustralia(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::LABOR_DAY,
            new DateTimeImmutable(sprintf('First Monday of %04d-10', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function addProclamationDay(HolidayList $holidays, int $year, int $additionalType = HolidayType::OTHER): void
    {
        if ($year <= 1992) {
            $holiday = Holiday::create(
                HolidayName::PROCLAMATION_DAY,
                sprintf('%04d-12-28', $year),
                HolidayType::OFFICIAL | $additionalType,
            );
            $holidays->add($holiday);

            return;
        }

        $holidays->add(Holiday::create(
            HolidayName::PROCLAMATION_DAY,
            sprintf('%04d-12-26', $year),
            HolidayType::OFFICIAL | $additionalType,
        ));
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        if ($year >= 1993) {
            return parent::getCompensatoryDaysCalculators($year);
        }

        return [
            ...parent::getCompensatoryDaysCalculators($year),
            new CompensatoryDaysCalculator([HolidayName::PROCLAMATION_DAY]),
        ];
    }
}
