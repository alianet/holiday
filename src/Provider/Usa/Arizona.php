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

final class Arizona extends State
{
    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;
    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(HolidayName::DR_MARTIN_LUTHER_KING_JR_CIVIL_RIGHTS_DAY, $this->weekday(1, 'Monday', 'third', 0));
        }
        if ($this->year >= 1971) {
            $this->add(HolidayName::LINCOLN_WASHINGTON_PRESIDENTS_DAY, $this->weekday(2, 'Monday', 'third', 0));
        }
    }
}
