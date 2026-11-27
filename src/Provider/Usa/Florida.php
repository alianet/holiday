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

final class Florida extends State
{
    use UsaHolidaysTrait;

    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 2011) {
            $this->add(HolidayName::SUSAN_B_ANTHONY_DAY, $this->fixed(2, 15));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
    }
}
