<?php

declare(strict_types=1);

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

final class NewMexico extends State
{
    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2019) ? HolidayName::INDIGENOUS_PEOPLES_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
        $this->add(HolidayName::PRESIDENTS_DAY, $this->weekday(11, 'Thursday', 'fourth', 1));
    }
}
