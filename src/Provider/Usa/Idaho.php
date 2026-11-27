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

final class Idaho extends State
{
    use UsaHolidaysTrait;

    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;
    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(($this->year >= 2006) ? HolidayName::MARTIN_LUTHER_KING_JR_IDAHO_HUMAN_RIGHTS_DAY : HolidayName::MARTIN_LUTHER_KING_JR_DAY, $this->weekday(1, 'Monday', 'third', 0));
        }
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
    }
}
