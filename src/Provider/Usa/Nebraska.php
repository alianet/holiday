<?php

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

final class Nebraska extends State
{
    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1875) {
            $name = HolidayName::ARBOR_DAY;
            if ($this->year >= 1989) {
                $this->add($name, $this->weekday(4, 'Friday', 'last', 0));
            } else {
                $this->add($name, $this->fixed(4, 22));
            }
        }
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2020) ? HolidayName::INDIGENOUS_PEOPLES_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
    }
}
