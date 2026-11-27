<?php

declare(strict_types=1);

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Filter;

use InvalidArgumentException;
use Umulmrum\Holiday\Assert\Assert;
use Umulmrum\Holiday\Model\Holiday;

use function array_flip;

/**
 * IncludeWeekdayFilter retains all holidays that are on a weekday passed as constructor argument.
 * Pass weekdays as separate arguments using constants in the Weekday class.
 */
final class IncludeWeekdayFilter extends AbstractFilter
{
    use Assert;

    /**
     * @var array<int, array-key>
     */
    private readonly array $weekdays;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(int ...$weekdays)
    {
        $this->assertArrayNotEmpty($weekdays);
        foreach ($weekdays as $weekday) {
            $this->assertWeekday($weekday);
        }
        $this->weekdays = array_flip($weekdays);
    }

    protected function isIncluded(Holiday $holiday): bool
    {
        return isset($this->weekdays[(int) $holiday->getDate()->format('w')]);
    }
}
