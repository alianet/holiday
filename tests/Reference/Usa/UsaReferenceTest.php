<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\Usa;

use DateTimeImmutable;
use Umulmrum\Holiday\Test\HolidayTestCase;

/** Snapshot integrity only: state providers are not implemented in the PHP library yet. */
final class UsaReferenceTest extends HolidayTestCase
{
    public function testSnapshotIntegrityAndCoverage(): void
    {
        $source = file_get_contents(__DIR__ . '/source.json');
        $content = file_get_contents(__DIR__ . '/Data/holidays.json');
        self::assertIsString($source);
        self::assertIsString($content);
        $manifest = json_decode($source, true, 512, JSON_THROW_ON_ERROR);
        $calendars = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($manifest);
        self::assertIsArray($calendars);
        self::assertSame(hash('sha256', $content), $manifest['sha256']);
        self::assertSame('holidays', $manifest['package']);
        self::assertSame('MIT', $manifest['license']);
        self::assertIsArray($manifest['subdivisions']);
        self::assertCount(51, $manifest['subdivisions']);
        self::assertIsArray($manifest['years']);
        $expected = [];
        foreach ($manifest['subdivisions'] as $subdivision) {
            self::assertIsString($subdivision);
            foreach ($manifest['years'] as $year) {
                self::assertIsInt($year);
                $expected[] = $subdivision . '-' . $year;
            }
        }
        $actual = [];
        foreach ($calendars as $calendar) {
            self::assertIsArray($calendar);
            self::assertIsString($calendar['subdivision']);
            self::assertIsInt($calendar['year']);
            $actual[] = $calendar['subdivision'] . '-' . $calendar['year'];
            foreach (['actual', 'withObserved'] as $mode) {
                self::assertIsArray($calendar[$mode]);
                self::assertNotEmpty($calendar[$mode]);
                $dates = [];
                foreach ($calendar[$mode] as $holiday) {
                    self::assertIsArray($holiday);
                    self::assertIsString($holiday['date']);
                    self::assertIsString($holiday['name']);
                    self::assertNotSame('', $holiday['name']);
                    self::assertMatchesRegularExpression('/^' . $calendar['year'] . '-\d{2}-\d{2}$/D', $holiday['date']);
                    self::assertNotFalse(DateTimeImmutable::createFromFormat('!Y-m-d', $holiday['date']));
                    self::assertSame($holiday['date'], (new DateTimeImmutable($holiday['date']))->format('Y-m-d'));
                    $dates[] = $holiday['date'];
                }
                self::assertSame(array_values(array_unique($dates)), $dates);
                $sorted = $dates;
                sort($sorted);
                self::assertSame($sorted, $dates);
            }
        }
        sort($expected);
        sort($actual);
        self::assertSame($expected, $actual);
    }
}
