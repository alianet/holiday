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

namespace Umulmrum\Holiday\Test\Provider;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Provider\Religion\ChristianOrthodoxHolidaysTrait;

final class ChristianOrthodoxHolidaysTraitTest extends TestCase
{
    #[DataProvider('provideEasterDates')]
    public function testEasterAndRelatedHolidays(int $year, string $easter, string $friday, string $monday, string $radonitsa, string $whitSunday): void
    {
        $provider = new class {
            use ChristianOrthodoxHolidaysTrait {
                getEasterSunday as public;
                getGoodFriday as public;
                getEasterMonday as public;
                getRadonitsa as public;
                getWhitSunday as public;
            }
        };

        self::assertSame($easter, $provider->getEasterSunday($year)->getSimpleDate());
        self::assertSame($friday, $provider->getGoodFriday($year)->getSimpleDate());
        self::assertSame($monday, $provider->getEasterMonday($year)->getSimpleDate());
        self::assertSame($radonitsa, $provider->getRadonitsa($year)->getSimpleDate());
        self::assertSame($whitSunday, $provider->getWhitSunday($year)->getSimpleDate());
    }

    /**
     * @return Generator<int, array{int, string, string, string, string, string}>
     */
    public static function provideEasterDates(): Generator
    {
        yield [1500, '1500-04-29', '1500-04-27', '1500-04-30', '1500-05-08', '1500-06-17'];

        yield [1600, '1600-04-02', '1600-03-31', '1600-04-03', '1600-04-11', '1600-05-21'];

        yield [1700, '1700-04-11', '1700-04-09', '1700-04-12', '1700-04-20', '1700-05-30'];

        yield [1800, '1800-04-20', '1800-04-18', '1800-04-21', '1800-04-29', '1800-06-08'];

        yield [1899, '1899-04-30', '1899-04-28', '1899-05-01', '1899-05-09', '1899-06-18'];

        yield [1900, '1900-04-22', '1900-04-20', '1900-04-23', '1900-05-01', '1900-06-10'];

        yield [2000, '2000-04-30', '2000-04-28', '2000-05-01', '2000-05-09', '2000-06-18'];

        yield [2024, '2024-05-05', '2024-05-03', '2024-05-06', '2024-05-14', '2024-06-23'];

        yield [2099, '2099-04-12', '2099-04-10', '2099-04-13', '2099-04-21', '2099-05-31'];

        yield [2100, '2100-05-02', '2100-04-30', '2100-05-03', '2100-05-11', '2100-06-20'];

        yield [2200, '2200-04-06', '2200-04-04', '2200-04-07', '2200-04-15', '2200-05-25'];
    }
}
