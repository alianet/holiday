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
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

final class Kentucky extends State
{
    use CommonHolidaysTrait;
    use ChristianHolidaysTrait;

    protected function populateStateHolidays(): void
    {
        $this->addHoliday($this->getGoodFriday($this->year));
        if ($this->year >= 2013) {
            $this->observe($this->addHoliday($this->getNewYearsEve($this->year)), null, null, true);
        }
    }
}
