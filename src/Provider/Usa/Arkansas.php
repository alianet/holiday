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

final class Arkansas extends State
{
    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;
    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(($this->year >= 2018) ? HolidayName::MARTIN_LUTHER_KING_JR_DAY : HolidayName::DR_MARTIN_LUTHER_KING_JR_AND_ROBERT_E_LEES_BIRTHDAYS, $this->weekday(1, 'Monday', 'third', 0));
        }
        if ($this->year >= 1971) {
            $this->add(HolidayName::GEORGE_WASHINGTONS_BIRTHDAY_AND_DAISY_GATSON_BATES_DAY, $this->weekday(2, 'Monday', 'third', 0));
        }
    }
}
