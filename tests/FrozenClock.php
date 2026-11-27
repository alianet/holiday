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

namespace Umulmrum\Holiday\Test;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final readonly class FrozenClock implements ClockInterface
{
    public function __construct(private DateTimeImmutable $dateTime) {}

    public function now(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
