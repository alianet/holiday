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

final class Delaware extends State
{
    use ChristianHolidaysTrait;
    use UsaHolidaysTrait;

    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;

    protected function populateStateHolidays(): void
    {
        $this->addHoliday($this->getGoodFriday($this->year));
        if (1971 <= $this->year && $this->year <= 2009) {
            $this->add(($this->year >= 1990) ? HolidayName::PRESIDENTS_DAY : HolidayName::WASHINGTONS_BIRTHDAY, $this->weekday(2, 'Monday', 'third', 0));
        }
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
    }
}
