<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Religion;

use DateInterval;
use DateTimeImmutable;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;

use function floor;
use function intdiv;
use function sprintf;

trait ChristianOrthodoxHolidaysTrait
{
    /** @var DateTimeImmutable[] */
    private array $easterCache = [];

    private function getEasterSundayDate(int $year): DateTimeImmutable
    {
        if (false === isset($this->easterCache[$year])) {
            $this->easterCache[$year] = $this->calculateEasterSunday($year);
        }

        return $this->easterCache[$year];
    }

    /**
     * Taken from https://en.wikipedia.org/wiki/Date_of_Easter#Meeus's_Julian_algorithm.
     * The Julian date is transformed into the Gregorian calendar using the offset for the given century.
     */
    private function calculateEasterSunday(int $year): DateTimeImmutable
    {
        $a = $year % 4;
        $b = $year % 7;
        $c = $year % 19;
        $d = (19 * $c + 15) % 30;
        $e = (2 * $a + 4 * $b - $d + 34) % 7;
        $month = floor(($d + $e + 114) / 31);
        $day = (($d + $e + 114) % 31) + 1;

        /** @var DateTimeImmutable $date */
        $date = DateTimeImmutable::createFromFormat(Holiday::DISPLAY_DATE_FORMAT, sprintf("%04d-{$month}-{$day}", $year));

        // Easter is always after February, so this year's century correction applies.
        $offset = intdiv($year, 100) - intdiv($year, 400) - 2;

        return $date->modify("{$offset} days");
    }

    private function getGoodFriday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $easterSunday = $this->getEasterSundayDate($year);

        return Holiday::createFromDateTime(HolidayName::GOOD_FRIDAY, $easterSunday->sub(new DateInterval('P2D')), HolidayType::RELIGIOUS | $additionalType);
    }

    private function getEasterSunday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $easterSunday = $this->getEasterSundayDate($year);

        return Holiday::createFromDateTime(HolidayName::EASTER_SUNDAY, $easterSunday, HolidayType::RELIGIOUS | $additionalType);
    }

    private function getEasterMonday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $easterSunday = $this->getEasterSundayDate($year);

        return Holiday::createFromDateTime(HolidayName::EASTER_MONDAY, $easterSunday->add(new DateInterval('P1D')), HolidayType::RELIGIOUS | $additionalType);
    }

    private function getRadonitsa(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $easterSunday = $this->getEasterSundayDate($year);

        return Holiday::createFromDateTime(HolidayName::RADONITSA, $easterSunday->add(new DateInterval('P9D')), HolidayType::RELIGIOUS | $additionalType);
    }

    private function getWhitSunday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $easterSunday = $this->getEasterSundayDate($year);

        return Holiday::createFromDateTime(HolidayName::WHIT_SUNDAY, $easterSunday->add(new DateInterval('P49D')), HolidayType::RELIGIOUS | $additionalType);
    }

    private function getChristmasDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::CHRISTMAS_DAY, sprintf('%04d-01-07', $year), HolidayType::RELIGIOUS | $additionalType);
    }
}
