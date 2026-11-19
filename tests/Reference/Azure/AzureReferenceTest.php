<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\Azure;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class AzureReferenceTest extends HolidayTestCase
{
    public function testEveryDocumentedDifferenceHasAnActiveCase(): void
    {
        $differences = self::readJson(__DIR__ . '/known-differences.json');
        $documented = array_keys($differences);
        $active = array_keys(iterator_to_array(self::provideKnownDifferences()));
        sort($documented);
        sort($active);
        self::assertSame($documented, $active, 'Documented discrepancies must not disappear by removing country/year coverage.');
    }

    #[DataProvider('provideCalendars')]
    public function testPublicHolidayDates(string $country, string $provider, int $year): void
    {
        $this->compareCalendar($country, $provider, $year, false);
    }

    /** @return Generator<string, array{string, string, int}> */
    public static function provideCalendars(): Generator
    {
        yield from self::provideCases(false);
    }

    #[DataProvider('provideKnownDifferences')]
    public function testDocumentedReferenceDiscrepancy(string $country, string $provider, int $year): void
    {
        $this->compareCalendar($country, $provider, $year, true);
    }

    /** @return Generator<string, array{string, string, int}> */
    public static function provideKnownDifferences(): Generator
    {
        yield from self::provideCases(true);
    }

    private function compareCalendar(string $country, string $provider, int $year, bool $knownDifference): void
    {
        $records = self::readJson(__DIR__ . '/Data/holidays.json');
        $names = self::readJson(__DIR__ . '/names.json');
        self::assertIsArray($names[$country]);
        $mapping = $names[$country];
        $scope = self::readJson(__DIR__ . '/scope.json');
        self::assertIsArray($scope['fullCalendarCountries']);
        $fullCalendar = in_array($country, $scope['fullCalendarCountries'], true);
        $comparedNames = [];
        foreach ($mapping as $identifiers) {
            self::assertIsArray($identifiers);
            foreach ($identifiers as $identifier) {
                self::assertIsString($identifier);
                $comparedNames[] = $identifier;
            }
        }
        $expected = [];
        foreach ($records as $record) {
            if (!is_array($record) || ($record['countryRegionCode'] ?? null) !== $country || !is_string($record['date'] ?? null) || !str_starts_with($record['date'], $year . '-')) {
                continue;
            }
            $name = $record['holidayName'];
            self::assertIsString($name);
            self::assertArrayHasKey($name, $mapping, 'Unmapped Azure holiday: ' . $name);
            self::assertIsArray($mapping[$name]);
            if ($mapping[$name] === []) {
                self::assertIsArray($scope['ignoredSourceNames']);
                self::assertIsArray($scope['ignoredSourceNames'][$country] ?? null);
                self::assertNotEmpty($scope['ignoredSourceNames'][$country][$name] ?? null, 'Excluded reference name requires a reason');

                continue;
            }
            foreach ($mapping[$name] as $identifier) {
                self::assertIsString($identifier);
                $expected[] = $record['date'] . ' ' . $identifier;
            }
        }
        self::assertNotEmpty($expected, 'Missing reference coverage');

        self::assertIsArray($scope['providerAliases']);
        $aliases = $scope['providerAliases'][$country] ?? [];
        self::assertIsArray($aliases);
        $actual = [];
        foreach ((new HolidayCalculator())->calculate($provider, $year) as $holiday) {
            if (!$fullCalendar) {
                if (($country === 'CH' && !$holiday->hasType(HolidayType::DAY_OFF)) || ($country === 'NL' && $holiday->getName() === HolidayName::DUTCH_LIBERATION_DAY && $year % 5 !== 0)) {
                    continue;
                }
                $name = $aliases[$holiday->getName()] ?? $holiday->getName();
                self::assertIsString($name);
                if (in_array($name, $comparedNames, true)) {
                    $actual[] = $holiday->getSimpleDate() . ' ' . $name;
                }

                continue;
            }
            // Azure names 1 January only as restoration of Czech statehood;
            // the provider also represents New Year on that same date.
            if ($country === 'CZ' && $holiday->getName() === HolidayName::NEW_YEAR) {
                continue;
            }
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
        if ($knownDifference) {
            $differences = self::readJson(__DIR__ . '/known-differences.json');
            $difference = $differences[$country . '-' . $year];
            self::assertIsArray($difference);
            self::assertIsString($difference['reason']);
            self::assertNotEmpty($difference['reason']);
            self::assertSame($difference['referenceOnly'], array_values(array_diff($expected, $actual)), 'Changed unresolved reference difference: ' . $difference['reason']);
            self::assertSame($difference['providerOnly'], array_values(array_diff($actual, $expected)), 'Changed unresolved provider difference: ' . $difference['reason']);
            // This regression test records an unresolved discrepancy; it does not
            // assert that either side is correct. Compare agreement separately.
            self::assertSame(array_values(array_intersect($expected, $actual)), array_values(array_intersect($actual, $expected)));
        } else {
            self::assertSame($expected, $actual, sprintf('%s %d differs from Azure Open Datasets: missing=%s; unexpected=%s', $provider, $year, implode(', ', array_diff($expected, $actual)), implode(', ', array_diff($actual, $expected))));
        }
    }

    /** @return Generator<string, array{string, string, int}> */
    private static function provideCases(bool $knownDifferences): Generator
    {
        $differences = self::readJson(__DIR__ . '/known-differences.json');
        $source = self::readJson(__DIR__ . '/source.json');
        self::assertIsArray($source['countries']);
        self::assertIsArray($source['years']);
        self::assertNotEmpty($source['countries']);
        self::assertNotEmpty($source['years']);
        foreach ($source['countries'] as $country) {
            self::assertIsString($country);
            foreach ($source['years'] as $year) {
                self::assertIsInt($year);

                if (isset($differences[$country . '-' . $year]) !== $knownDifferences) {
                    continue;
                }

                yield $country . '-' . $year => [$country, $country, $year];
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
