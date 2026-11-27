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

final class NewHampshire extends State
{
    use UsaHolidaysTrait;

    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(HolidayName::DR_MARTIN_LUTHER_KING_JR_CIVIL_RIGHTS_DAY, $this->weekday(1, 'Monday', 'third', 0));
        }
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
    }
}
