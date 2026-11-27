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

final class Colorado extends State
{
    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->add(HolidayName::WASHINGTON_LINCOLN_DAY, $this->weekday(2, 'Monday', 'third', 0));
        }
        if ($this->year >= 2001) {
            $this->add(HolidayName::CESAR_CHAVEZ_DAY, $this->fixed(3, 31));
        }
        if ($this->year >= 2020) {
            $this->add(HolidayName::FRANCES_XAVIER_CABRINI_DAY, $this->weekday(10, 'Monday', 'first', 0));
        }
    }
}
