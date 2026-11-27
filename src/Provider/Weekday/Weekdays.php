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

namespace Umulmrum\Holiday\Provider\Weekday;

use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

abstract class Weekdays implements HolidayProviderInterface
{
    use WeekdayTrait;

    public function __construct(private int $weekday, private int $additionalType = HolidayType::OTHER) {}

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        return new HolidayList($this->getWeekdays($year, $this->weekday, $this->additionalType));
    }
}
