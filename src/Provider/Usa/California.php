<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Usa;

use InvalidArgumentException;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Provider\Usa\Internal\DiwaliDates;

final class California extends State
{
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;

    protected function populateStateHolidays(): void
    {
        if (1971 <= $this->year && $this->year <= 2009) {
            $this->observe($this->addHoliday($this->getLincolnsBirthday($this->year)));
        }
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        if ($this->year >= 2014) {
            $this->add(HolidayName::SUSAN_B_ANTHONY_DAY, $this->fixed(2, 15));
        }
        if ($this->year >= 1995) {
            $this->observe($this->add(HolidayName::CESAR_CHAVEZ_DAY, $this->fixed(3, 31)), null, 'sunday', true);
        }
        if (1971 <= $this->year && $this->year <= 2008) {
            $this->add(HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
        if ($this->year >= 2026) {
            $this->diwali(HolidayName::DIWALI);
        }
    }

    private function diwali(string $name): void
    {
        if (!isset(DiwaliDates::DATES[$this->year])) {
            throw new InvalidArgumentException('California Diwali dates are only supported through 2100.');
        }
        [$month, $day] = DiwaliDates::DATES[$this->year];
        $this->add($name, $this->fixed($month, $day));
    }
}
