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

use Umulmrum\Holiday\Constant\HolidayName;

final class Virginia extends State
{
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;

    protected function populateStateHolidays(): void
    {
        if (1889 <= $this->year && $this->year <= 2020) {
            $name = HolidayName::LEE_JACKSON_DAY;
            if ($this->year >= 2000) {
                $this->add($name, $this->weekday(1, 'Monday', 'third', -3));
            } else {
                if ($this->year >= 1983) {
                    $this->add($name, $this->weekday(1, 'Monday', 'third', 0));
                } else {
                    $this->add($name, $this->fixed(1, 19));
                }
            }
        }
        if (($this->year >= 1789) && ((($this->year - 1789) % 4) === 0)) {
            $name = HolidayName::INAUGURATION_DAY;
            $this->observe(($this->year >= 1937) ? $this->add($name, $this->fixed(1, 20)) : $this->add($name, $this->fixed(3, 4)), null, 'sunday', true);
        }
        if ($this->year >= 1971) {
            $this->add(HolidayName::GEORGE_WASHINGTON_DAY, $this->weekday(2, 'Monday', 'third', 0));
            $this->add(($this->year >= 2020) ? HolidayName::INDIGENOUS_PEOPLES_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
        if ($this->year >= 2020) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
    }
}
