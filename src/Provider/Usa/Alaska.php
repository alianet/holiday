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

final class Alaska extends State
{
    use UsaHolidaysTrait;

    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;
    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;

    protected function populateStateHolidays(): void
    {
        if ($this->year <= 1958) {
            return;
        }
        if ($this->year >= 1990) {
            $this->addHoliday($this->getMartinLutherKingJrDay($this->year));
        } else {
            $this->observe($this->addHoliday($this->getLincolnsBirthday($this->year)), null, null, true);
        }
        if ($this->year >= 1971) {
            $this->add(($this->year >= 1990) ? HolidayName::PRESIDENTS_DAY : HolidayName::WASHINGTONS_BIRTHDAY, $this->weekday(2, 'Monday', 'third', 0));
        }
        $this->add(HolidayName::SEWARDS_DAY, $this->weekday(3, 'Monday', 'last', 0));
        if ($this->year >= 1971) {
            $this->add(($this->year >= 2015) ? HolidayName::INDIGENOUS_PEOPLES_DAY : HolidayName::COLUMBUS_DAY, $this->weekday(10, 'Monday', 'second', 0));
        }
        $this->observe($this->add(HolidayName::ALASKA_DAY, $this->fixed(10, 18)), null, null, true);
    }
}
