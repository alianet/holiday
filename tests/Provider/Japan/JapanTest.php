<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Provider\Japan;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Japan\Japan;

final class JapanTest extends TestCase
{
    /** @param list<string> $expected */
    #[DataProvider('calendars')]
    public function testOfficialCalendar(int $year, array $expected): void
    {
        $actual = [];
        foreach ((new HolidayCalculator())->calculate('JP', $year) as $holiday) {
            $actual[] = substr($holiday->getSimpleDate(), 5);
            self::assertSame(HolidayType::OFFICIAL | HolidayType::DAY_OFF, $holiday->getType() & (HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        }
        self::assertSame($expected, $actual);
    }

    /** @return iterable<array{int, list<string>}> */
    public static function calendars(): iterable
    {
        // Cabinet Office: https://www8.cao.go.jp/chosei/shukujitsu/gaiyou.html
        yield [2026, ['01-01', '01-12', '02-11', '02-23', '03-20', '04-29', '05-03', '05-04', '05-05', '05-06', '07-20', '08-11', '09-21', '09-22', '09-23', '10-12', '11-03', '11-23']];

        yield [2027, ['01-01', '01-11', '02-11', '02-23', '03-21', '03-22', '04-29', '05-03', '05-04', '05-05', '07-19', '08-11', '09-20', '09-23', '10-11', '11-03', '11-23']];

        yield [2019, ['01-01', '01-14', '02-11', '03-21', '04-29', '04-30', '05-01', '05-02', '05-03', '05-04', '05-05', '05-06', '07-15', '08-11', '08-12', '09-16', '09-23', '10-14', '10-22', '11-03', '11-04', '11-23']];

        yield [2020, ['01-01', '01-13', '02-11', '02-23', '02-24', '03-20', '04-29', '05-03', '05-04', '05-05', '05-06', '07-23', '07-24', '08-10', '09-21', '09-22', '11-03', '11-23']];

        yield [2021, ['01-01', '01-11', '02-11', '02-23', '03-20', '04-29', '05-03', '05-04', '05-05', '07-22', '07-23', '08-08', '08-09', '09-20', '09-23', '11-03', '11-23']];
    }

    public function testHistoricalRulesAndTypes(): void
    {
        $provider = new Japan();
        $dates = [];
        foreach ($provider->calculateHolidaysForYear(1998) as $holiday) {
            $dates[$holiday->getSimpleDate()] = $holiday;
        }
        self::assertArrayHasKey('1998-01-15', $dates);
        self::assertArrayHasKey('1998-07-20', $dates);
        self::assertArrayHasKey('1998-09-15', $dates);
        self::assertArrayHasKey('1998-10-10', $dates);
        self::assertArrayHasKey('1998-12-23', $dates);
        self::assertArrayNotHasKey('1998-08-11', $dates);
        self::assertSame(HolidayName::GREENERY_DAY, $dates['1998-04-29']->getName());
        self::assertSame(HolidayName::JAPAN_CONSTITUTION_DAY . HolidayName::SUFFIX_COMPENSATORY, $dates['1998-05-04']->getName());
        self::assertNotSame(0, $dates['1998-05-04']->getType() & HolidayType::COMPENSATORY);

        $dates = [];
        foreach ($provider->calculateHolidaysForYear(2026) as $holiday) {
            $dates[$holiday->getSimpleDate()] = $holiday;
        }
        self::assertSame(HolidayName::CITIZENS_HOLIDAY, $dates['2026-09-22']->getName());
        self::assertSame(0, $dates['2026-09-22']->getType() & HolidayType::COMPENSATORY);
        self::assertSame(HolidayName::JAPAN_CONSTITUTION_DAY . HolidayName::SUFFIX_COMPENSATORY, $dates['2026-05-06']->getName());
    }

    #[DataProvider('unsupportedYears')]
    public function testRejectsUnsupportedYears(int $year): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Japan())->calculateHolidaysForYear($year);
    }

    /** @return iterable<array{int}> */
    public static function unsupportedYears(): iterable
    {
        foreach ([0, 1979, 2100, 10000] as $year) {
            yield [$year];
        }
    }

    public function testSupportedBoundaries(): void
    {
        foreach ([1980, 2099] as $year) {
            self::assertGreaterThan(0, (new Japan())->calculateHolidaysForYear($year)->count());
        }
    }
}
