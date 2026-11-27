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

final class Maryland extends State
{
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if (($this->year >= 1789) && ((($this->year - 1789) % 4) === 0)) {
            $name = HolidayName::INAUGURATION_DAY;
            $this->observe(($this->year >= 1937) ? $this->add($name, $this->fixed(1, 20)) : $this->add($name, $this->fixed(3, 4)), null, 'sunday', true);
        }
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        if ($this->year >= 2008) {
            $this->add(HolidayName::AMERICAN_INDIAN_HERITAGE_DAY, $this->weekday(11, 'Thursday', 'fourth', 1));
        }
    }
}
