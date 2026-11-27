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

namespace Umulmrum\Holiday\Provider\Mexico;

use DateTime;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Constant\Weekday;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Mexico implements CompensatoryHolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::DAY_OFF));
        $this->addConstitutionDay($holidays, $year);
        $holidays->add($this->getBirthdayOfBenitoJuarez($holidays, $year));
        if ($year >= 1923) {
            $holidays->add($this->getLaborDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1826) {
            $holidays->add($this->getIndependenceDay($year));
        }
        $this->addRevolutionDay($holidays, $year);
        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));

        return $holidays;
    }

    private function addConstitutionDay(HolidayList $holidays, int $year): void
    {
        if ($year >= 2006) {
            $date = (new DateTime(sprintf('First Monday of %04d-02', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } elseif ($year >= 1918) {
            $date = sprintf('%04d-02-05', $year);
        } else {
            return;
        }
        $holiday = Holiday::create(HolidayName::CONSTITUTION_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        $holidays->add($holiday);
    }

    private function getBirthdayOfBenitoJuarez(HolidayList $holidays, int $year): Holiday
    {
        if ($year >= 2006) {
            $date = (new DateTime(sprintf('Third Monday of %04d-03', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } else {
            $date = sprintf('%04d-02-05', $year);
        }

        return Holiday::create(HolidayName::BIRTHDAY_OF_BENITO_JUAREZ, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }

    private function getIndependenceDay(int $year): Holiday
    {
        return Holiday::create(HolidayName::INDEPENDENCE_DAY, sprintf('%04d-09-16', $year), HolidayType::OFFICIAL | HolidayType::DAY_OFF);
    }

    private function addRevolutionDay(HolidayList $holidays, int $year): void
    {
        if ($year >= 2006) {
            $date = (new DateTime(sprintf('Third Monday of %04d-11', $year)))->format(Holiday::DISPLAY_DATE_FORMAT);
        } elseif ($year >= 1911) {
            $date = sprintf('%04d-11-20', $year);
        } else {
            return;
        }
        $holiday = Holiday::create(HolidayName::REVOLUTION_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        $holidays->add($holiday);
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        return [
            new CompensatoryDaysCalculator(
                weekDaysToStepBackward: [Weekday::SATURDAY],
                weekDaysToStepForward: [Weekday::SUNDAY],
            ),
        ];
    }
}
