<?php

declare(strict_types=1);

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

final class Alabama extends State
{
    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;
    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(HolidayName::MARTIN_LUTHER_KING_JR_ROBERT_E_LEES_BIRTHDAY, $this->weekday(1, 'Monday', 'third', 0));
        }
        if ($this->year >= 1971) {
            $this->add(HolidayName::GEORGE_WASHINGTON_THOMAS_JEFFERSONS_BIRTHDAY, $this->weekday(2, 'Monday', 'third', 0));
        }
        if ($this->year >= 1866) {
            $this->add(HolidayName::CONFEDERATE_MEMORIAL_DAY, $this->weekday(4, 'Monday', 'fourth', 0));
        }
        if ($this->year >= 1890) {
            $this->add(HolidayName::JEFFERSON_DAVIS_BIRTHDAY, $this->weekday(6, 'Monday', 'first', 0));
        }
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2000) ? HolidayName::COLUMBUS_DAY_AMERICAN_INDIAN_HERITAGE_DAY_FRATERNAL_DAY : HolidayName::COLUMBUS_DAY_FRATERNAL_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
    }
}
