<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Reference\Spain;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Spain\Tenerife;
use Umulmrum\Holiday\Test\HolidayTestCase;
use Umulmrum\Holiday\Translator\Translator;

use function file_get_contents;
use function json_decode;
use function sort;

final class IslandReferenceTest extends HolidayTestCase
{
    /** @param list<string> $extraDates */
    #[DataProvider('provideCalendars')]
    public function testIslandCalendar(string $island, string $region, int $year, array $extraDates): void
    {
        $calculator = new HolidayCalculator();
        $expected = [];
        foreach ($calculator->calculate($region, $year) as $holiday) {
            $expected[] = $holiday->getSimpleDate();
        }
        foreach ($extraDates as $date) {
            $expected[] = $year . '-' . $date;
        }
        $actual = [];
        $translator = new Translator();
        foreach ($calculator->calculate('Umulmrum\Holiday\Provider\Spain\\' . $island, $year) as $holiday) {
            $actual[] = $holiday->getSimpleDate();
            self::assertTrue($holiday->hasType(HolidayType::OFFICIAL | HolidayType::DAY_OFF));
            self::assertNotSame('', $translator->translateName($holiday));
        }
        sort($expected);
        sort($actual);
        self::assertSame($expected, $actual);
    }

    /** @return Generator<string, array{string, string, int, list<string>}> */
    public static function provideCalendars(): Generator
    {
        /** @var array<int, array<string, list<array{date: string, name: string, compensatory: bool}>>> $calendars */
        $calendars = json_decode(file_get_contents(__DIR__ . '/Data/islands.json') ?: '', true, flags: JSON_THROW_ON_ERROR);
        foreach ($calendars as $year => $islands) {
            foreach ($islands as $island => $rows) {
                $dates = [];
                foreach ($rows as $row) {
                    $dates[] = $row['date'];
                }

                yield $island . '/' . $year => [$island, $island === 'Formentera' ? 'ES-IB' : 'ES-CN', $year, $dates];
            }
        }
    }

    public function testHistoricalIdentitiesAndSubstitutions(): void
    {
        $calculator = new HolidayCalculator();
        foreach ([
            ['ElHierro', 2009, 'descent_of_our_lady_of_the_kings', '2009-07-04', false],
            ['ElHierro', 2023, 'our_lady_of_the_kings_compensatory', '2023-09-25', true],
            ['LaPalma', 2012, 'saint_michael_day', '2012-09-29', false],
            ['LaPalma', 2018, 'saint_michael_day', '2018-09-29', false],
            ['Tenerife', 2020, 'descent_of_our_lady_of_socorro', '2020-09-07', false],
            ['Tenerife', 2014, 'our_lady_of_candelaria_compensatory', '2014-02-03', true],
            ['GranCanaria', 2013, 'our_lady_of_the_pine_compensatory', '2013-09-09', true],
            ['Lanzarote', 2019, 'our_lady_of_sorrows_compensatory', '2019-09-16', true],
            ['Formentera', 2022, 'saint_ferdinand_day', '2022-05-30', false],
            ['Formentera', 2023, 'second_christmas_day', '2023-12-26', false],
        ] as [$island, $year, $name, $date, $compensatory]) {
            $matches = $calculator->calculate('Umulmrum\Holiday\Provider\Spain\\' . $island, $year)->getByName($name);
            self::assertCount(1, $matches);
            foreach ($matches as $holiday) {
                self::assertSame($date, $holiday->getSimpleDate());
                self::assertSame($compensatory, $holiday->hasType(HolidayType::COMPENSATORY));
            }
        }
        $provider = new Tenerife();
        $first = $provider->calculateHolidaysForYear(2008);
        $provider->calculateHolidaysForYear(2020);
        self::assertEquals($first, $provider->calculateHolidaysForYear(2008));
    }

    #[DataProvider('provideUnsupportedYears')]
    public function testUnsupportedYears(int $year): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new HolidayCalculator())->calculate(Tenerife::class, $year);
    }

    /** @return Generator<int, array{int}> */
    public static function provideUnsupportedYears(): Generator
    {
        foreach ([0, 2007, 2028, 10000] as $year) {
            yield [$year];
        }
    }
}
