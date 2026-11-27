<?php

declare(strict_types=1);

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Provider;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class VictoryDayTest extends HolidayTestCase
{
    #[DataProvider('provideVictoryDays')]
    public function testVictoryDay(string $provider, int $year, string $name, ?string $date, int $type): void
    {
        $matches = [];
        foreach ((new HolidayCalculator())->calculate($provider, $year) as $holiday) {
            if ($holiday->getName() === $name) {
                $matches[] = $holiday;
            }
        }

        self::assertCount($date === null ? 0 : 1, $matches);
        if ($date !== null) {
            self::assertSame($date, $matches[0]->getSimpleDate());
            self::assertSame($type, $matches[0]->getType());
        }
    }

    /**
     * @return Generator<int, array{string, int, string, ?string, int}>
     */
    public static function provideVictoryDays(): Generator
    {
        foreach ([2019, 2020, 2021, 2024, 2025, 2026] as $year) {
            yield ['DE-BE', $year, HolidayName::VICTORY_IN_EUROPE_DAY, $year === 2020 || $year === 2025 ? "{$year}-05-08" : null, HolidayType::DAY_OFF];
        }

        // The public holiday was restored in October 1981, and first fell in 1982.
        foreach (['FR', 'FR-67', 'FR-68', 'FR-57', 'FR-GF', 'FR-GUA', 'FR-MQ', 'FR-LRE', 'FR-YT', 'FR-BL', 'FR-MF'] as $provider) {
            foreach ([1944, 1945, 1952, 1953, 1958, 1959, 1964, 1965, 1966, 1980, 1981, 1982, 2025] as $year) {
                $dayOff = ($year >= 1953 && $year <= 1958) || $year === 1965 || $year >= 1982;

                yield [$provider, $year, HolidayName::VICTORY_DAY, $dayOff ? "{$year}-05-08" : null, HolidayType::OFFICIAL | HolidayType::DAY_OFF];
            }
        }

        foreach ([1951, 1952, 1990, 1991, 1992, 2025] as $year) {
            $date = $year < 1952 ? null : ($year < 1992 ? "{$year}-05-09" : "{$year}-05-08");

            yield ['CZ', $year, HolidayName::VICTORY_DAY, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF];
        }

        foreach (['BY', 'UA'] as $provider) {
            foreach ([1944, 1945, 1947, 1948, 1964, 1965, 1966] as $year) {
                $type = $year >= 1948 && $year <= 1964 ? HolidayType::OFFICIAL : HolidayType::OFFICIAL | HolidayType::DAY_OFF;

                yield [$provider, $year, HolidayName::VICTORY_DAY, $year >= 1945 ? "{$year}-05-09" : null, $type];
            }
        }

        yield ['UA', 2023, HolidayName::DAY_OF_VICTORY_OVER_NAZISM, '2023-05-09', HolidayType::OFFICIAL];

        yield ['UA', 2024, HolidayName::DAY_OF_VICTORY_OVER_NAZISM, null, HolidayType::OTHER];

        yield ['UA', 2024, HolidayName::DAY_OF_REMEMBRANCE_AND_VICTORY_OVER_NAZISM, '2024-05-08', HolidayType::OFFICIAL];
    }
}
