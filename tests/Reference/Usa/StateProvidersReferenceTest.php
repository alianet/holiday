<?php

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\Usa;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Usa\State;
use Umulmrum\Holiday\Resolver\IsoResolver;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class StateProvidersReferenceTest extends HolidayTestCase
{
    /** @param list<array{date: string, name: string}> $expected */
    #[DataProvider('provideCalendars')]
    public function testStateCalendar(string $code, int $year, bool $observed, array $expected): void
    {
        $source = file_get_contents(__DIR__ . '/names.json');
        self::assertIsString($source);
        $names = json_decode($source, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($names);
        $provider = (new IsoResolver())->resolveHolidayProvider($code);
        // Prevent silently comparing the federal fallback instead of the requested state.
        self::assertInstanceOf(State::class, $provider);
        $holidays = $observed ? (new HolidayCalculator())->calculate($code, $year) : $provider->calculateHolidaysForYear($year);
        $expectedPairs = [];
        foreach ($expected as $row) {
            foreach (explode('; ', $row['name']) as $name) {
                $isObserved = str_ends_with($name, ' (observed)');
                $label = $isObserved ? substr($name, 0, -11) : $name;
                self::assertArrayHasKey($label, $names, 'Unmapped external holiday: ' . $label);
                self::assertIsString($names[$label]);
                $expectedPairs[] = $row['date'] . ' ' . $names[$label] . ($isObserved ? HolidayName::SUFFIX_COMPENSATORY : '');
            }
        }
        $actualPairs = [];
        foreach ($holidays as $holiday) {
            $actualPairs[] = $holiday->getSimpleDate() . ' ' . $holiday->getName();
        }
        $expectedPairs = array_values(array_unique($expectedPairs));
        sort($expectedPairs);
        sort($actualPairs);
        self::assertSame($expectedPairs, $actualPairs, $code . ' ' . $year . ($observed ? ' observed' : ' actual'));
    }

    /** @return Generator<string, array{string, int, bool, list<array{date: string, name: string}>}> */
    public static function provideCalendars(): Generator
    {
        $content = file_get_contents(__DIR__ . '/Data/holidays.json');
        self::assertIsString($content);
        $calendars = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($calendars);
        foreach ($calendars as $calendar) {
            self::assertIsArray($calendar);
            self::assertIsString($calendar['subdivision']);
            self::assertIsInt($calendar['year']);
            foreach (['actual' => false, 'withObserved' => true] as $mode => $observed) {
                self::assertIsArray($calendar[$mode]);
                $rows = [];
                foreach ($calendar[$mode] as $row) {
                    self::assertIsArray($row);
                    self::assertIsString($row['date']);
                    self::assertIsString($row['name']);
                    $rows[] = ['date' => $row['date'], 'name' => $row['name']];
                }

                yield $calendar['subdivision'] . '-' . $calendar['year'] . '-' . $mode => ['US-' . $calendar['subdivision'], $calendar['year'], $observed, $rows];
            }
        }
    }
}
