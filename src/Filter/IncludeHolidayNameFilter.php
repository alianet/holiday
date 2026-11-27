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

use Umulmrum\Holiday\Model\Holiday;

use function array_flip;

/**
 * IncludeHolidayNameFilter retains all holidays with the name or names passed as constructor arguments.
 */
final class IncludeHolidayNameFilter extends AbstractFilter
{
    /**
     * @var array<string, array-key>
     */
    private array $holidayNames;

    public function __construct(string ...$holidayNames)
    {
        $this->holidayNames = array_flip($holidayNames);
    }

    protected function isIncluded(Holiday $holiday): bool
    {
        return isset($this->holidayNames[$holiday->getName()]);
    }
}
