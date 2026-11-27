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

namespace Umulmrum\Holiday;

use InvalidArgumentException;
use TypeError;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

interface HolidayCalculatorInterface
{
    /**
     * Calculates all holidays for a given $year.
     *
     * @param HolidayProviderInterface|HolidayProviderInterface[]|string|string[] $holidayProviders
     * @param int|int[]                                                           $years
     *
     * @return HolidayList order is not guaranteed; use filters to sort the list afterwards
     *
     * @throws InvalidArgumentException if a provider cannot be resolved or a year value is invalid
     * @throws TypeError                if an argument has an incompatible type
     */
    public function calculate(array|HolidayProviderInterface|string $holidayProviders, array|int $years): HolidayList;
}
