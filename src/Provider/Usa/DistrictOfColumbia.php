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

final class DistrictOfColumbia extends State
{
    protected function populateStateHolidays(): void
    {
        if (($this->year >= 1789) && ((($this->year - 1789) % 4) === 0)) {
            $name = HolidayName::INAUGURATION_DAY;
            $this->observe(($this->year >= 1937) ? $this->add($name, $this->fixed(1, 20)) : $this->add($name, $this->fixed(3, 4)), null, 'sunday', true);
        }
        if ($this->year >= 2005) {
            $this->observe($this->add(HolidayName::EMANCIPATION_DAY, $this->fixed(4, 16)), null, null, true);
        }
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2019) ? HolidayName::INDIGENOUS_PEOPLES_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
    }
}
