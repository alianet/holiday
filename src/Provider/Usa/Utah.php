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

final class Utah extends State
{
    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->add(HolidayName::WASHINGTON_AND_LINCOLN_DAY, $this->weekday(2, 'Monday', 'third', 0));
        }
        if ($this->year >= 1849) {
            $this->observe($this->add(HolidayName::PIONEER_DAY, $this->fixed(7, 24)), null, null, true);
        }
    }
}
