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
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Spain\Internal\CalendarData;

use function explode;
use function in_array;
use function sprintf;

/**
 * Published BOE regional calendars, including their selected substitute dates.
 * Municipal/island holidays and the Aran variant are outside this calendar's scope.
 */
abstract class Region implements HolidayProviderInterface
{
    /** @var string */
    protected const CODE = '';

    final public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);
        if (!isset(CalendarData::YEARS[$year])) {
            throw new InvalidArgumentException('Spanish regional calendars are supported for 2008–2027 only.');
        }

        $holidays = new HolidayList();
        foreach (CalendarData::YEARS[$year] as [$date, $name, $type, $regions]) {
            if (in_array(static::CODE, explode(' ', $regions), true)) {
                $holidays->add(Holiday::create($name, sprintf('%04d-%s', $year, $date), $type));
            }
        }

        return $holidays;
    }
}
