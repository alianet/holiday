<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Provider;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

final class ChristianHolidaysTraitTest extends TestCase
{
    #[DataProvider('providePalmSundayDates')]
    public function testPalmSunday(int $year, string $date): void
    {
        $provider = new class {
            use ChristianHolidaysTrait {
                getPalmSunday as public;
            }
        };

        self::assertEquals(
            Holiday::create(HolidayName::PALM_SUNDAY, $date, HolidayType::RELIGIOUS),
            $provider->getPalmSunday($year),
        );
        self::assertEquals(
            Holiday::create(HolidayName::PALM_SUNDAY, $date, HolidayType::RELIGIOUS | HolidayType::OFFICIAL | HolidayType::DAY_OFF),
            $provider->getPalmSunday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF),
        );
    }

    /**
     * @return Generator<int, array{int, string}>
     */
    public static function providePalmSundayDates(): Generator
    {
        yield [2008, '2008-03-16'];

        yield [2024, '2024-03-24'];

        yield [2025, '2025-04-13'];
    }
}
