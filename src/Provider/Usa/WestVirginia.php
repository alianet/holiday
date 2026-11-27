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

final class WestVirginia extends State
{
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        if ($this->year >= 1927) {
            $this->observe($this->add(HolidayName::WEST_VIRGINIA_DAY, $this->fixed(6, 20)), null, null, true);
        }
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
    }
}
