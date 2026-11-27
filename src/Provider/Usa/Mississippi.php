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

final class Mississippi extends State
{
    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(HolidayName::DR_MARTIN_LUTHER_KING_JR_AND_ROBERT_E_LEES_BIRTHDAYS, $this->weekday(1, 'Monday', 'third', 0));
        }
        if ($this->year >= 1866) {
            $this->add(HolidayName::CONFEDERATE_MEMORIAL_DAY, $this->weekday(4, 'Monday', 'last', 0));
        }
    }
}
