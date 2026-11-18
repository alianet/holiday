<?php

namespace Umulmrum\Holiday\Assert;

use InvalidArgumentException;

final class Year
{
    public static function validate(int $year): void
    {
        if ($year < 1 || $year > 9999) {
            throw new InvalidArgumentException('Year must be between 1 and 9999, got ' . $year);
        }
    }
}
