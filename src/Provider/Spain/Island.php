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

namespace Umulmrum\Holiday\Provider\Spain;

use InvalidArgumentException;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

use function sprintf;
use function str_ends_with;

/**
 * Island additions to the regional calendar. See tests/Reference/Spain/Islands.md.
 */
abstract class Island implements HolidayProviderInterface
{
    protected const REGION = '';

    /** @var array<int, list<array{string, string}>> */
    protected const HOLIDAYS = [];

    final public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);
        if (!isset(static::HOLIDAYS[$year])) {
            throw new InvalidArgumentException('Spanish island calendars are supported for 2008–2027 only.');
        }

        $provider = static::REGION === 'CN' ? new CanaryIslands() : new BalearicIslands();
        $holidays = $provider->calculateHolidaysForYear($year);
        foreach (static::HOLIDAYS[$year] as [$date, $name]) {
            $holidays->add(Holiday::create($name, sprintf('%04d-%s', $year, $date), HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::RELIGIOUS | (str_ends_with($name, '_compensatory') ? HolidayType::COMPENSATORY : 0)));
        }

        return $holidays;
    }
}
