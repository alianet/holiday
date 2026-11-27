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

final class Texas extends State
{
    use ChristianHolidaysTrait;
    use UsaHolidaysTrait;

    protected const WASHINGTONS_BIRTHDAY_ON_THIRD_MONDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1931) {
            $this->add(HolidayName::CONFEDERATE_MEMORIAL_DAY, $this->fixed(1, 19));
        }
        if ($this->year >= 1971) {
            $this->addHoliday($this->getPresidentsDay($this->year));
        }
        if ($this->year >= 1874) {
            $this->add(HolidayName::TEXAS_INDEPENDENCE_DAY, $this->fixed(3, 2));
        }
        if ($this->year >= 2000) {
            $this->add(HolidayName::CESAR_CHAVEZ_DAY, $this->fixed(3, 31));
        }
        $this->addHoliday($this->getGoodFriday($this->year));
        if ($this->year >= 1875) {
            $this->add(HolidayName::SAN_JACINTO_DAY, $this->fixed(4, 21));
        }
        if ($this->year >= 1980) {
            $this->add(HolidayName::EMANCIPATION_DAY_IN_TEXAS, $this->fixed(6, 19));
        }
        if ($this->year >= 1973) {
            $this->add(HolidayName::LYNDON_BAINES_JOHNSON_DAY, $this->fixed(8, 27));
        }
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
        if ($this->year >= 1981) {
            $this->christmasEve();
            $this->add(HolidayName::DAY_AFTER_CHRISTMAS, $this->fixed(12, 26));
        }
    }
}
