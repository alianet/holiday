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

final class RhodeIsland extends State
{
    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1948) {
            $this->add(HolidayName::VICTORY_DAY, $this->weekday(8, 'Monday', 'second', 0));
        }
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2022) ? HolidayName::INDIGENOUS_PEOPLES_DAY_COLUMBUS_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
    }
}
