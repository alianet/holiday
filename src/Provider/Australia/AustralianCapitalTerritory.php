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
use Umulmrum\Holiday\Constant\Weekday;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class AustralianCapitalTerritory extends Australia
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        $holidays = parent::calculateHolidaysForYear($year);
        $holidays->add($this->getCanberraDay($year, HolidayType::DAY_OFF));
        $holidays->add($this->getEasterSaturday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($year >= 2018) {
            $holidays->add($this->getReconciliationDay($year, HolidayType::DAY_OFF));
        }
        $holidays->add($this->getLaborDayAct($year, HolidayType::DAY_OFF));

        return $holidays;
    }

    protected function getCanberraDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year <= 2007) {
            $date = new DateTimeImmutable(sprintf('Third Monday of %04d-03', $year));
        } else {
            $date = new DateTimeImmutable(sprintf('Second Monday of %04d-03', $year));
        }

        return Holiday::createFromDateTime(
            HolidayName::CANBERRA_DAY,
            $date,
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function getReconciliationDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::RECONCILIATION_DAY,
            new DateTimeImmutable(sprintf('%04d-05-27 this Monday', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    protected function getLaborDayAct(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(
            HolidayName::LABOR_DAY,
            new DateTimeImmutable(sprintf('First Monday of %04d-10', $year)),
            HolidayType::OFFICIAL | $additionalType,
        );
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        return [
            ...parent::getCompensatoryDaysCalculators($year),
            new CompensatoryDaysCalculator(
                forTheseHolidayNamesOnly: [HolidayName::ANZAC_DAY],
                weekDaysToStepForward: [Weekday::SUNDAY],
            ),
        ];
    }
}
