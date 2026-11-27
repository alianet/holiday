<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Filter;

use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;

/**
 * IncludeTypeFilter retains all holidays with one or more types passed as constructor argument.
 * Pass type constants or integer bitmasks as separate arguments.
 */
final class IncludeTypeFilter extends AbstractFilter
{
    private int $holidayTypes = HolidayType::OTHER;

    public function __construct(int ...$holidayTypes)
    {
        foreach ($holidayTypes as $holidayType) {
            $this->holidayTypes |= $holidayType;
        }
    }

    protected function isIncluded(Holiday $holiday): bool
    {
        return $holiday->hasType($this->holidayTypes);
    }
}
