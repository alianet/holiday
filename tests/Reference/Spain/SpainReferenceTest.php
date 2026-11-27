<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\Spain;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Spain\Region;
use Umulmrum\Holiday\Resolver\IsoResolver;
use Umulmrum\Holiday\Test\HolidayTestCase;
use Umulmrum\Holiday\Translator\Translator;

use function file_get_contents;
use function json_decode;
use function sort;

final class SpainReferenceTest extends HolidayTestCase
{
    private const REGIONS = ['AN', 'AR', 'AS', 'CB', 'CE', 'CL', 'CM', 'CN', 'CT', 'EX', 'GA', 'IB', 'MC', 'MD', 'ML', 'NC', 'PV', 'RI', 'VC'];

    #[DataProvider('provideCalendars')]
    public function testDatesMatchPublishedBoeAnnex(string $region, int $year): void
    {
        /** @var array<int, list<array{date: string, label: string, regions: array<string, string>}>> $annexes */
        $annexes = json_decode(file_get_contents(__DIR__ . '/Data/annexes.json') ?: '', true, flags: JSON_THROW_ON_ERROR);
        $expected = [];
        foreach ($annexes[$year] as $row) {
            if (isset($row['regions'][$region])) {
                $expected[] = $year . '-' . $row['date'];
            }
        }
        self::assertNotEmpty($expected);
        self::assertInstanceOf(Region::class, (new IsoResolver())->resolveHolidayProvider('ES-' . $region));

        $actual = [];
        $translator = new Translator('en');
        foreach ((new HolidayCalculator())->calculate('ES-' . $region, $year) as $holiday) {
            self::assertTrue($holiday->hasType(HolidayType::OFFICIAL));
            self::assertTrue($holiday->hasType(HolidayType::DAY_OFF));
            self::assertNotSame('', $translator->translateName($holiday));
            $actual[] = $holiday->getSimpleDate();
        }
        sort($expected);
        sort($actual);
        self::assertSame($expected, $actual);
    }

    /** @return Generator<string, array{string, int}> */
    public static function provideCalendars(): Generator
    {
        foreach (self::REGIONS as $region) {
            for ($year = 2008; $year <= 2027; ++$year) {
                yield $region . '/' . $year => [$region, $year];
            }
        }
    }

    #[DataProvider('provideSelectedHolidays')]
    public function testSelectedIdentityAndClassification(string $provider, int $year, string $name, ?string $date, int $type): void
    {
        $matches = (new HolidayCalculator())->calculate($provider, $year)->getByName($name);
        self::assertCount($date === null ? 0 : 1, $matches);
        foreach ($matches as $holiday) {
            self::assertSame($date, $holiday->getSimpleDate());
            self::assertSame($type, $holiday->getType());
        }
    }

    /** @return Generator<int, array{string, int, string, string|null, int}> */
    public static function provideSelectedHolidays(): Generator
    {
        $public = HolidayType::OFFICIAL | HolidayType::DAY_OFF;
        $religious = $public | HolidayType::RELIGIOUS;

        yield ['ES', 1989, HolidayName::IMMACULATE_CONCEPTION, '1989-12-08', $religious];

        yield ['ES', 2026, HolidayName::ALL_SAINTS_DAY, '2026-11-01', $religious];

        yield ['ES-AN', 2026, HolidayName::ANDALUSIA_DAY, '2026-02-28', $public];

        yield ['ES-AN', 2027, HolidayName::ANDALUSIA_DAY_COMPENSATORY, '2027-03-01', $public | HolidayType::COMPENSATORY];

        yield ['ES-CT', 2026, HolidayName::MAUNDY_THURSDAY, null, $religious];

        yield ['ES-CT', 2026, HolidayName::EASTER_MONDAY, '2026-04-06', $religious];

        yield ['ES-CL', 2026, HolidayName::CASTILE_AND_LEON_DAY, '2026-04-23', $public];

        yield ['ES-AR', 2026, HolidayName::ARAGON_DAY, '2026-04-23', $public];

        yield ['ES-GA', 2026, HolidayName::GALICIAN_NATIONAL_DAY, '2026-07-25', $religious];

        yield ['ES-CE', 2027, HolidayName::EID_AL_ADHA, '2027-05-17', $religious];

        yield ['ES-ML', 2027, HolidayName::EID_AL_FITR, '2027-03-10', $religious];

        yield ['ES-EX', 2027, HolidayName::ASSUMPTION_DAY_COMPENSATORY, '2027-10-11', $religious | HolidayType::COMPENSATORY];

        yield ['ES-AN', 2027, HolidayName::ASSUMPTION_DAY_COMPENSATORY, '2027-08-16', $religious | HolidayType::COMPENSATORY];

        yield ['ES-PV', 2027, HolidayName::BASQUE_GOVERNMENT_ANNIVERSARY, '2027-10-07', $public];

        yield ['ES-CE', 2026, HolidayName::OUR_LADY_OF_AFRICA, '2026-08-05', $religious];
    }

    #[DataProvider('provideUnsupportedYears')]
    public function testRegionalCalendarRejectsUnsupportedYears(int $year): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new HolidayCalculator())->calculate('ES-MD', $year);
    }

    /** @return Generator<int, array{int}> */
    public static function provideUnsupportedYears(): Generator
    {
        foreach ([0, 1978, 2007, 2028, 10000] as $year) {
            yield [$year];
        }
    }

    public function testRegionalProviderCanBeReused(): void
    {
        $provider = (new IsoResolver())->resolveHolidayProvider('ES-AN');
        self::assertInstanceOf(Region::class, $provider);
        $first = $provider->calculateHolidaysForYear(2008);
        $provider->calculateHolidaysForYear(2027);
        self::assertEquals($first, $provider->calculateHolidaysForYear(2008));
    }
}
