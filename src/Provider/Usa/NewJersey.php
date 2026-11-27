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

use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

final class NewJersey extends State
{
    use ChristianHolidaysTrait;
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->observe($this->addHoliday($this->getLincolnsBirthday($this->year)), null, null, true);
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        $this->addHoliday($this->getGoodFriday($this->year));
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
    }
}
