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

final class Louisiana extends State
{
    use ChristianHolidaysTrait;
    use UsaHolidaysTrait;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1857) {
            $this->addHoliday($this->getFatTuesday($this->year), HolidayName::MARDI_GRAS);
        }
        $this->addHoliday($this->getGoodFriday($this->year));
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
    }
}
