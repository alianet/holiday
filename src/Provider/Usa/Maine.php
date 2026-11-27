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

final class Maine extends State
{
    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1894) {
            $name = HolidayName::PATRIOTS_DAY;
            if ($this->year >= 1969) {
                $this->add(HolidayName::PATRIOTS_DAY, $this->weekday(4, 'Monday', 'third', 0));
            } else {
                $this->add($name, $this->fixed(4, 19));
            }
        }
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2019) ? HolidayName::INDIGENOUS_PEOPLES_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
    }
}
