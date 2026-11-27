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

final class Washington extends State
{
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        if ($this->year >= 2014) {
            $this->add(HolidayName::NATIVE_AMERICAN_HERITAGE_DAY, $this->weekday(11, 'Thursday', 'fourth', 1));
        }
    }
}
