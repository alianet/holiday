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

final class NorthCarolina extends State
{
    use ChristianHolidaysTrait;
    use UsaHolidaysTrait;

    protected function populateStateHolidays(): void
    {
        $this->addHoliday($this->getGoodFriday($this->year));
        if ($this->year >= 1975) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
        if ($this->year >= 2013) {
            $this->christmasEve();
        }
        if ($this->year >= 2013) {
            $name = HolidayName::DAY_AFTER_CHRISTMAS;
            $this->observe($this->add($name, $this->fixed(12, 26)), $name, 'boxingDay', true);
        }
    }
}
