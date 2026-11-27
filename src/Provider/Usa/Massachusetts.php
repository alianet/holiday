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

final class Massachusetts extends State
{
    protected const INCLUDE_COLUMBUS_DAY = true;
    protected const OBSERVE_SATURDAYS = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1894) {
            $name = HolidayName::PATRIOTS_DAY;
            if ($this->year >= 1969) {
                $this->add($name, $this->weekday(4, 'Monday', 'third', 0));
            } else {
                $this->add($name, $this->fixed(4, 19));
            }
        }
    }
}
