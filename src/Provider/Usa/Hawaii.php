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

final class Hawaii extends State
{
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        if ($this->year >= 1949) {
            $this->observe($this->add(HolidayName::PRINCE_JONAH_KUHIO_KALANIANAOLE_DAY, $this->fixed(3, 26)), null, null, true);
        }
        if ($this->year >= 1872) {
            $jun_11 = $this->add(HolidayName::KAMEHAMEHA_DAY, $this->fixed(6, 11));
            if ($this->year >= 2011) {
                $this->observe($jun_11, null, null, true);
            }
        }
        if ($this->year >= 1959) {
            $this->add(HolidayName::STATEHOOD_DAY, $this->weekday(8, 'Friday', 'third', 0));
        }
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
    }
}
