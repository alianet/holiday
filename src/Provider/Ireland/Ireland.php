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

namespace Umulmrum\Holiday\Provider\Ireland;

use DateTime;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Ireland implements CompensatoryHolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::DAY_OFF));
        if ($year >= 2023) {
            $holidays->add($this->getStBrigidsDayHoliday($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1903) {
            $holidays->add($this->getSaintPatricksDay($year));
        }
        $holidays->add($this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::BANK | HolidayType::NO_SCHOOL));
        $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));

        if ($year >= 1994) {
            $holidays->add($this->getMayDay($year, HolidayType::DAY_OFF));
        }
        if ($year <= 1973) {
            $holidays->add($this->getWhitMonday($year, HolidayType::DAY_OFF));
        } else {
            $holidays->add($this->getJuneHoliday($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1871) {
            $holidays->add($this->getAugustHoliday($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1977) {
            $holidays->add($this->getOctoberHoliday($year, HolidayType::DAY_OFF));
        }
        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getSecondChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));

        return $holidays;
    }

    private function getSaintPatricksDay(int $year, int $additionalTyoe = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::SAINT_PATRICKS_DAY, sprintf('%04d-03-17', $year), HolidayType::OFFICIAL | HolidayType::DAY_OFF | $additionalTyoe);
    }

    private function getMayDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('First Monday of %04d-05', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::MAY_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getJuneHoliday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('First Monday of %04d-06', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::JUNE_HOLIDAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getAugustHoliday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('First Monday of %04d-08', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::AUGUST_HOLIDAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    private function getOctoberHoliday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = (new DateTime(sprintf('Last Monday of %04d-10', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::OCTOBER_HOLIDAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    /**
     * St Brigids day is a new holiday (2023) that is celebrated on first monday of February unless Feb 1 falls on
     * a friday then it is on that Friday.
     */
    private function getStBrigidsDayHoliday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $dateToCheck = new DateTime(sprintf('%04d-02-01', $year));
        if ($dateToCheck->format('w') !== '5') {
            $dateToCheck = new DateTime(sprintf('First Monday of %04d-2', $year));
        }
        $date = $dateToCheck->format(Holiday::DISPLAY_DATE_FORMAT);

        return Holiday::create(HolidayName::SAINT_BRIGIDS_DAY, $date, HolidayType::OFFICIAL | $additionalType);
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        return [
            new CompensatoryDaysCalculator(
                [
                    HolidayName::NEW_YEAR,
                    HolidayName::SAINT_PATRICKS_DAY,
                    HolidayName::CHRISTMAS_DAY,
                    HolidayName::SECOND_CHRISTMAS_DAY,
                ],
            ),
        ];
    }
}
