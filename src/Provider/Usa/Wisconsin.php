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
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;

final class Wisconsin extends State
{
    use CommonHolidaysTrait;

    protected function populateStateHolidays(): void
    {
        if ($this->year >= 1976) {
            $this->add(HolidayName::SUSAN_B_ANTHONY_DAY, $this->fixed(2, 15));
        }
        if ($this->year >= 2012) {
            $this->christmasEve();
            $this->observe($this->addHoliday($this->getNewYearsEve($this->year)), null, null, true);
        }
    }
}
