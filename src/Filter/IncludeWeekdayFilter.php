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

use InvalidArgumentException;
use Umulmrum\Holiday\Constant\Weekday;
use Umulmrum\Holiday\Model\Holiday;

use function array_flip;
use function sprintf;

/**
 * IncludeWeekdayFilter retains all holidays that are on a weekday passed as constructor argument.
 * Pass weekdays as separate arguments using constants in the Weekday class.
 */
final class IncludeWeekdayFilter extends AbstractFilter
{
    /**
     * @var array<int, array-key>
     */
    private readonly array $weekdays;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(int ...$weekdays)
    {
        if ($weekdays === []) {
            throw new InvalidArgumentException('array must not be empty');
        }
        foreach ($weekdays as $weekday) {
            if ($weekday < Weekday::SUNDAY || $weekday > Weekday::SATURDAY) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid weekday "%s". Expected value between %s and %s.',
                    $weekday,
                    Weekday::SUNDAY,
                    Weekday::SATURDAY,
                ));
            }
        }
        $this->weekdays = array_flip($weekdays);
    }

    protected function isIncluded(Holiday $holiday): bool
    {
        return isset($this->weekdays[(int) $holiday->getDate()->format('w')]);
    }
}
