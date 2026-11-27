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

final class Iowa extends State
{
    use UsaHolidaysTrait;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->observe($this->addHoliday($this->getLincolnsBirthday($this->year)), null, null, true);
        }
    }
}
