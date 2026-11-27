<?php

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Stefan Kruppa
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Usa;

use DateTime;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;

/** Shared holiday dates; providers decide applicability and substitute-day rules. */
trait UsaHolidaysTrait
{
    private function getMartinLutherKingJrDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('Third Monday of %04d-01', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::MARTIN_LUTHER_KING_JR_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getWashingtonsBirthday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year >= 1971) {
            $date = (new DateTime(sprintf('Third Monday of %04d-02', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } else {
            $date = sprintf('%04d-02-22', $year);
        }

        return Holiday::create(HolidayName::WASHINGTONS_BIRTHDAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getMemorialDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year >= 1971) {
            $date = (new DateTime(sprintf('Last Monday of %04d-05', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } else {
            $date = sprintf('%04d-05-30', $year);
        }

        return Holiday::create(HolidayName::MEMORIAL_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getLaborDayUsa(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('First Monday of %04d-09', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::LABOR_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getColumbusDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        if ($year >= 1971) {
            $date = (new DateTime(sprintf('Second Monday of %04d-10', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } else {
            $date = sprintf('%04d-10-12', $year);
        }

        return Holiday::create(HolidayName::COLUMBUS_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getArmisticeDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::ARMISTICE_DAY, sprintf('%04d-11-11', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getThanksgivingDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('Fourth Thursday of %04d-11', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::THANKSGIVING_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getIndependenceDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::INDEPENDENCE_DAY, sprintf('%04d-07-04', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getJuneteenth(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::JUNETEENTH_NATIONAL_INDEPENDENCE_DAY, sprintf('%04d-06-19', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getLincolnsBirthday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::LINCOLNS_BIRTHDAY, sprintf('%04d-02-12', $year), HolidayType::OFFICIAL | $additionalType);
    }

    private function getPresidentsDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::PRESIDENTS_DAY, (new DateTime(sprintf('Third Monday of %04d-02', $year)))->format(Holiday::DISPLAY_DATE_FORMAT), HolidayType::OFFICIAL | $additionalType);
    }

    private function getElectionDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::ELECTION_DAY, (new DateTime(sprintf('First Monday of %04d-11', $year)))->modify('+1 day')->format(Holiday::DISPLAY_DATE_FORMAT), HolidayType::OFFICIAL | $additionalType);
    }

    private function getDayAfterThanksgiving(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::DAY_AFTER_THANKSGIVING, (new DateTime(sprintf('Fourth Thursday of %04d-11', $year)))->modify('+1 day')->format(Holiday::DISPLAY_DATE_FORMAT), HolidayType::OFFICIAL | $additionalType);
    }
}
