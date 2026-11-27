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

namespace Umulmrum\Holiday\Test\Reference\OpenHoliday;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class OpenHolidaysReferenceTest extends HolidayTestCase
{
    #[DataProvider('provideCalendars')]
    public function testPublicHolidayDates(string $country, string $provider, int $year): void
    {
        $records = self::readJson(__DIR__ . '/Data/' . $country . '.json');
        $names = self::readJson(__DIR__ . '/names.json');
        self::assertIsArray($names[$country]);
        $mapping = $names[$country];
        $expected = [];
        foreach ($records as $record) {
            self::assertIsArray($record);
            self::assertIsString($record['startDate']);
            if (!str_starts_with($record['startDate'], $year . '-')) {
                continue;
            }
            $applies = $record['nationwide'] === true;
            if (!$applies && $provider !== $country) {
                self::assertIsArray($record['subdivisions'] ?? null);
                foreach ($record['subdivisions'] as $subdivision) {
                    self::assertIsArray($subdivision);
                    $applies = $applies || $subdivision['code'] === $provider;
                }
            }
            if (!$applies) {
                continue;
            }
            self::assertSame('Public', $record['type']);
            self::assertSame('FullDay', $record['temporalScope']);
            self::assertSame($record['startDate'], $record['endDate']);
            self::assertIsArray($record['name']);
            self::assertCount(1, $record['name']);
            self::assertIsArray($record['name'][0]);
            self::assertSame('EN', $record['name'][0]['language']);
            $name = $record['name'][0]['text'];
            self::assertIsString($name);
            self::assertArrayHasKey($name, $mapping, 'Unmapped reference holiday: ' . $name);
            self::assertIsString($mapping[$name]);
            $expected[] = $record['startDate'] . ' ' . $mapping[$name];
        }
        self::assertNotEmpty($expected, 'Missing reference coverage');

        $actual = [];
        foreach ((new HolidayCalculator())->calculate($provider, $year) as $holiday) {
            // Poland currently omits DAY_OFF on both Christmas holidays.
            // Compare their identity and dates without claiming to validate classification.
            $polishChristmas = $country === 'PL' && in_array($holiday->getName(), [HolidayName::CHRISTMAS_DAY, HolidayName::SECOND_CHRISTMAS_DAY], true);
            if (($holiday->hasType(HolidayType::DAY_OFF) && !$holiday->hasType(HolidayType::PARTIAL_ONLY)) || $polishChristmas) {
                $actual[] = $holiday->getSimpleDate() . ' ' . $holiday->getName();
            }
        }
        // Poland also represents Armed Forces Day on the same public holiday as Assumption.
        if ($country === 'PL') {
            $actual = array_values(array_filter($actual, static fn (string $entry): bool => !str_ends_with($entry, ' ' . HolidayName::POLISH_ARMED_FORCES_DAY)));
        }
        sort($expected);
        sort($actual);
        self::assertSame($expected, $actual, sprintf('%s %d differs from OpenHolidays: missing=%s; unexpected=%s', $provider, $year, implode(', ', array_diff($expected, $actual)), implode(', ', array_diff($actual, $expected))));
    }

    /** @return Generator<string, array{string, string, int}> */
    public static function provideCalendars(): Generator
    {
        foreach (['PL' => ['PL'], 'DE' => ['DE', 'DE-BE'], 'CZ' => ['CZ'], 'AT' => ['AT']] as $country => $providers) {
            foreach ($providers as $provider) {
                foreach ([2024, 2025] as $year) {
                    yield $provider . '-' . $year => [$country, $provider, $year];
                }
            }
        }
    }

    /** @return array<mixed> */
    private static function readJson(string $path): array
    {
        $content = file_get_contents($path);
        self::assertIsString($content);
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);

        return $data;
    }
}
