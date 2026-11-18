<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Provider\Poland;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Provider\Poland\Poland;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class PolandTest extends HolidayTestCase
{
    #[DataProvider('provideEpiphanyYears')]
    public function testEpiphany(int $year, bool $expected): void
    {
        $epiphany = [];
        foreach ((new Poland())->calculateHolidaysForYear($year) as $holiday) {
            if ($holiday->getName() === HolidayName::EPIPHANY) {
                $epiphany[] = $holiday;
            }
        }

        self::assertCount($expected ? 1 : 0, $epiphany);
        if ($expected) {
            self::assertSame("{$year}-01-06", $epiphany[0]->getSimpleDate());
            self::assertSame(HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::RELIGIOUS, $epiphany[0]->getType());
        }
    }

    /**
     * @return Generator<int, array{int, bool}>
     */
    public static function provideEpiphanyYears(): Generator
    {
        yield [1925, true];

        yield [1959, true];

        yield [1960, true];

        yield [1961, false];

        yield [2010, false];

        yield [2011, true];
    }
}
