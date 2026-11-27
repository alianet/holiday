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

use Umulmrum\Holiday\Provider\CommonHolidaysTrait;

final class Michigan extends State
{
    use CommonHolidaysTrait;
    use UsaHolidaysTrait;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 2013) {
            $this->christmasEve();
            $this->observe($this->addHoliday($this->getNewYearsEve($this->year)), null, null, true);
        }
        if (($this->year >= 2008) && (($this->year % 2) === 0)) {
            $this->addHoliday($this->getElectionDay($this->year));
        }
        if ($this->year >= 2017) {
            $this->addHoliday($this->getDayAfterThanksgiving($this->year));
        }
    }
}
