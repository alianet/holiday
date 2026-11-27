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

final class SouthDakota extends State
{
    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1937) {
            $name = (($this->year >= 1990) ? HolidayName::NATIVE_AMERICANS_DAY : HolidayName::COLUMBUS_DAY);
            if ($this->year >= 1970) {
                $this->add($name, $this->weekday(10, 'Monday', 'second', 0));
            } else {
                $this->add($name, $this->fixed(10, 12));
            }
        }
    }
}
