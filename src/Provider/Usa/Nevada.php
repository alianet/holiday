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

final class Nevada extends State
{
    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1933) {
            $name = HolidayName::NEVADA_DAY;
            $this->observe(($this->year >= 2000) ? $this->add($name, $this->weekday(10, 'Friday', 'last', 0)) : $this->add($name, $this->fixed(10, 31)), null, null, true);
        }
        $this->add(HolidayName::FAMILY_DAY, $this->weekday(11, 'Thursday', 'fourth', 1));
    }
}
