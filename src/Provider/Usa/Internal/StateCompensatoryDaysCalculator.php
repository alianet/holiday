<?php

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Provider\Usa\Internal;

use Closure;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculatorInterface;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

/** @internal */
final readonly class StateCompensatoryDaysCalculator implements CompensatoryDaysCalculatorInterface
{
    /** @param Closure(int): HolidayList $calculate */
    public function __construct(private Closure $calculate) {}

    public function addAll(HolidayList $holidays, HolidayProviderInterface $holidayProvider, int $year): void
    {
        foreach (($this->calculate)($year) as $holiday) {
            if ($holiday->hasType(HolidayType::COMPENSATORY)) {
                $holidays->add($holiday);
            }
        }
    }
}
