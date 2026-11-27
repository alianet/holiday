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

final class NewYork extends State
{
    use UsaHolidaysTrait;

    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->observe($this->addHoliday($this->getLincolnsBirthday($this->year)), null, null, true);
        }
        if ($this->year >= 2004) {
            $this->add(HolidayName::SUSAN_B_ANTHONY_DAY, $this->fixed(2, 15));
        }
        if (($this->year >= 2015) || (($this->year >= 2008) && (($this->year % 2) === 0))) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
    }
}
