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

final class Illinois extends State
{
    use UsaHolidaysTrait;

    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->observe($this->addHoliday($this->getLincolnsBirthday($this->year)), null, null, true);
        }
        if ($this->year >= 1978) {
            $this->add(HolidayName::CASIMIR_PULASKI_DAY, $this->weekday(3, 'Monday', 'first', 0));
        }
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
    }
}
