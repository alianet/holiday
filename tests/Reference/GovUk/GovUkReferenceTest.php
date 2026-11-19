<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\GovUk;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class GovUkReferenceTest extends HolidayTestCase
{
    #[DataProvider('provideCalendars')]
    public function testBankHolidays(string $division, string $provider, int $year): void
    {
        $data = self::readJson(__DIR__ . '/Data/bank-holidays.json');
        $mapping = self::readJson(__DIR__ . '/names.json');
        self::assertIsArray($data[$division]);
        self::assertSame($division, $data[$division]['division']);
        self::assertIsArray($data[$division]['events']);
        $expected = [];
        foreach ($data[$division]['events'] as $event) {
            self::assertIsArray($event);
            self::assertIsString($event['date']);
            if (!str_starts_with($event['date'], $year . '-')) {
                continue;
            }
            self::assertIsString($event['title']);
            self::assertIsString($event['notes']);
            self::assertArrayHasKey($event['title'], $mapping, 'Unmapped GOV.UK holiday: ' . $event['title']);
            self::assertIsString($mapping[$event['title']]);
            // GOV.UK lists the observed date and marks replacement days explicitly.
            $suffix = $event['notes'] === 'Substitute day' ? HolidayName::SUFFIX_COMPENSATORY : '';
            self::assertContains($event['notes'], ['', 'Substitute day']);
            $expected[] = $event['date'] . ' ' . self::normalizeName($division, $mapping[$event['title']] . $suffix);
        }
        self::assertNotEmpty($expected, 'No GOV.UK reference events for ' . $division . ' ' . $year);

        $actual = [];
        $holidays = (new HolidayCalculator())->calculate($provider, $year);
        foreach ($holidays as $holiday) {
            // The library retains original weekend dates alongside compensatory days.
            // Remove an original only when a compensatory entry actually exists.
            if (!$holiday->hasType(HolidayType::COMPENSATORY)
                && $holidays->getByName($holiday->getName() . HolidayName::SUFFIX_COMPENSATORY) !== []) {
                continue;
            }
            $actual[] = $holiday->getSimpleDate() . ' ' . self::normalizeName($division, $holiday->getName());
        }
        sort($expected);
        sort($actual);
        self::assertSame($expected, $actual, sprintf('%s %d differs from GOV.UK: missing=%s; unexpected=%s', $provider, $year, implode(', ', array_diff($expected, $actual)), implode(', ', array_diff($actual, $expected))));
    }

    /** @return Generator<string, array{string, string, int}> */
    public static function provideCalendars(): Generator
    {
        $source = self::readJson(__DIR__ . '/source.json');
        self::assertIsArray($source['years']);
        self::assertNotEmpty($source['years']);
        foreach (['england-and-wales' => 'GB', 'scotland' => 'GB-SCT', 'northern-ireland' => 'GB-NIR'] as $division => $provider) {
            foreach ($source['years'] as $year) {
                self::assertIsInt($year);

                yield $provider . '-' . $year => [$division, $provider, $year];
            }
        }
    }

    private static function normalizeName(string $division, string $name): string
    {
        // The provider and GOV.UK assign different labels to the same two
        // Scottish New Year bank holidays when 1 January is a Sunday.
        if ($division === 'scotland' && in_array($name, [HolidayName::NEW_YEAR, HolidayName::NEW_YEAR_HOLIDAY, HolidayName::NEW_YEAR_COMPENSATORY, HolidayName::NEW_YEAR_HOLIDAY . HolidayName::SUFFIX_COMPENSATORY], true)) {
            return 'scottish_new_year_bank_holiday';
        }

        return $name;
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
