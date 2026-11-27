<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Provider\Canada;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Canada\Federal;

final class CanadaTest extends TestCase
{
    /** @param list<string> $expected */
    #[DataProvider('provideCalendars')]
    public function testGeneralHolidayDates(string $code, array $expected): void
    {
        $dates = [];
        foreach ((new HolidayCalculator())->calculate($code, 2026) as $holiday) {
            if (($holiday->getType() & HolidayType::DAY_OFF) !== 0 && ($holiday->getType() & HolidayType::PARTIAL_ONLY) === 0) {
                $dates[] = substr($holiday->getSimpleDate(), 5);
            }
        }
        $dates = array_values(array_unique($dates));
        sort($dates);
        sort($expected);
        self::assertSame($expected, $dates);
    }

    /** @return iterable<string, array{string, list<string>}> */
    public static function provideCalendars(): iterable
    {
        $base = ['01-01', '04-03', '07-01', '09-07', '12-25'];

        yield 'national common dates' => ['CA', $base];

        yield 'Alberta' => ['CA-AB', [...$base, '02-16', '05-18', '10-12', '11-11']];

        yield 'British Columbia' => ['CA-BC', [...$base, '02-16', '05-18', '08-03', '09-30', '10-12', '11-11']];

        yield 'Manitoba' => ['CA-MB', [...$base, '02-16', '05-18', '09-30', '10-12']];

        yield 'New Brunswick' => ['CA-NB', [...$base, '02-16', '08-03', '11-11']];

        yield 'Newfoundland and Labrador' => ['CA-NL', [...$base, '11-11']];

        yield 'Northwest Territories' => ['CA-NT', [...$base, '05-18', '06-21', '08-03', '09-30', '10-12', '11-11']];

        yield 'Nova Scotia' => ['CA-NS', [...$base, '02-16']];

        yield 'Nunavut' => ['CA-NU', [...$base, '05-18', '07-09', '08-03', '09-30', '10-12', '11-11']];

        yield 'Ontario' => ['CA-ON', [...$base, '02-16', '05-18', '10-12', '12-26']];

        yield 'Prince Edward Island' => ['CA-PE', [...$base, '02-16', '09-30', '11-11']];

        // Either Good Friday or Easter Monday is selected by the employer.
        yield 'Quebec unconditional dates' => ['CA-QC', ['01-01', '05-18', '06-24', '07-01', '09-07', '10-12', '12-25']];

        yield 'Saskatchewan' => ['CA-SK', [...$base, '02-16', '05-18', '08-03', '10-12', '11-11']];

        yield 'Yukon' => ['CA-YT', [...$base, '05-18', '06-21', '08-17', '09-30', '10-12', '11-11']];
    }

    #[DataProvider('provideTransitions')]
    public function testTransitions(string $code, int $year, string $name, ?string $date, bool $partial): void
    {
        $holidays = (new HolidayCalculator())->calculate($code, $year)->getByName($name);
        self::assertCount($date === null ? 0 : 1, $holidays);
        if ($date !== null) {
            self::assertSame($date, $holidays[0]->getSimpleDate());
            self::assertSame($partial, ($holidays[0]->getType() & HolidayType::PARTIAL_ONLY) !== 0);
        }
    }

    /** @return iterable<array{string, int, string, ?string, bool}> */
    public static function provideTransitions(): iterable
    {
        yield ['CA-BC', 2012, HolidayName::FAMILY_DAY, null, false];

        yield ['CA-BC', 2013, HolidayName::FAMILY_DAY, '2013-02-11', false];

        yield ['CA-BC', 2018, HolidayName::FAMILY_DAY, '2018-02-12', false];

        yield ['CA-BC', 2019, HolidayName::FAMILY_DAY, '2019-02-18', false];

        yield ['CA-PE', 2009, HolidayName::ISLANDER_DAY, '2009-02-09', false];

        yield ['CA-PE', 2010, HolidayName::ISLANDER_DAY, '2010-02-15', false];
        foreach (['CA-MB' => 2024, 'CA-NU' => 2022, 'CA-NT' => 2022, 'CA-PE' => 2022, 'CA-BC' => 2023, 'CA-YT' => 2023] as $code => $first) {
            yield [$code, $first - 1, HolidayName::NATIONAL_DAY_FOR_TRUTH_AND_RECONCILIATION, ($first - 1) . '-09-30', true];

            yield [$code, $first, HolidayName::NATIONAL_DAY_FOR_TRUTH_AND_RECONCILIATION, $first . '-09-30', false];
        }

        yield ['CA-NU', 2019, HolidayName::NUNAVUT_DAY, '2019-07-09', true];

        yield ['CA-NU', 2020, HolidayName::NUNAVUT_DAY, '2020-07-09', false];

        yield ['CA', 2022, HolidayName::NATIONAL_DAY_OF_MOURNING, '2022-09-19', true];

        yield ['CA', 2018, HolidayName::CANADA_DAY_COMPENSATORY, '2018-07-02', false];

        yield ['CA', 2023, HolidayName::CANADA_DAY_COMPENSATORY, null, false];
    }

    public function testFederalSector(): void
    {
        $holidays = (new Federal())->calculateHolidaysForYear(2026);
        self::assertCount(10, $holidays);
        foreach ($holidays as $holiday) {
            self::assertSame(0, $holiday->getType() & HolidayType::PARTIAL_ONLY);
        }
        self::assertCount(9, (new Federal())->calculateHolidaysForYear(2020));
        self::assertCount(0, (new Federal())->calculateHolidaysForYear(2022)->getByName(HolidayName::NATIONAL_DAY_OF_MOURNING));
    }
}
