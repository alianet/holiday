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
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

final class Indiana extends State
{
    use ChristianHolidaysTrait;
    use UsaHolidaysTrait;

    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        $this->addHoliday($this->getGoodFriday($this->year));
        if (($this->year >= 2015) || (($this->year >= 2006) && (($this->year % 2) === 0))) {
            $this->add(HolidayName::PRIMARY_ELECTION_DAY, $this->weekday(5, 'Monday', 'first', 1));
        }
        if (($this->year >= 2015) || (($this->year >= 2008) && (($this->year % 2) === 0))) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
        if ($this->year >= 2010) {
            $this->add(HolidayName::LINCOLNS_BIRTHDAY, $this->weekday(11, 'Thursday', 'fourth', 1));
        }
        if ($this->year >= 1971) {
            $this->observe($this->fixed(12, 25), HolidayName::WASHINGTONS_BIRTHDAY, 'washington', false);
        }
    }
}
