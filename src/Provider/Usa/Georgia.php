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
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

final class Georgia extends State
{
    use ChristianHolidaysTrait;

    protected const INCLUDE_COMMON_MARTIN_LUTHER_KING_DAY = false;
    protected const INCLUDE_WASHINGTONS_BIRTHDAY = false;
    protected const INCLUDE_COLUMBUS_DAY = true;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1986) {
            $this->add(($this->year >= 2012) ? HolidayName::MARTIN_LUTHER_KING_JR_DAY : HolidayName::ROBERT_E_LEES_BIRTHDAY, $this->weekday(1, 'Monday', 'third', 0));
        }
        if ($this->year >= 1866) {
            $name = (($this->year >= 2016) ? HolidayName::STATE_HOLIDAY : HolidayName::CONFEDERATE_MEMORIAL_DAY);
            if ($this->year >= 2020) {
                $this->addHoliday($this->getGoodFriday($this->year), $name);
            } else {
                $this->add($name, $this->weekday(4, 'Monday', 'fourth', 0));
            }
        }
        if ($this->year >= 1986) {
            $this->add(($this->year >= 2016) ? HolidayName::STATE_HOLIDAY : HolidayName::ROBERT_E_LEES_BIRTHDAY, $this->weekday(11, 'Thursday', 'fourth', 1));
        }
        if ($this->year >= 1971) {
            $this->observe($this->fixed(12, 25), HolidayName::WASHINGTONS_BIRTHDAY, 'washington', false);
        }
    }
}
