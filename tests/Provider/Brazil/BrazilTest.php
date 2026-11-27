<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Provider\Brazil;

use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Provider\Brazil\Brazil;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class BrazilTest extends HolidayTestCase
{
    #[DataProvider('provideHolidays')]
    public function testHoliday(int $year, string $name, ?string $date, int $type): void
    {
        $matches = [];
        foreach ((new Brazil())->calculateHolidaysForYear($year) as $holiday) {
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

    /** @return iterable<string, array{int, string, ?string, int}> */
    public static function provideHolidays(): iterable
    {
        $official = HolidayType::OFFICIAL | HolidayType::DAY_OFF;

        yield 'before national introduction' => [2023, HolidayName::BLACK_CONSCIOUSNESS_DAY, null, 0];

        yield 'first national observance' => [2024, HolidayName::BLACK_CONSCIOUSNESS_DAY, '2024-11-20', $official];

        yield 'current national observance' => [2026, HolidayName::BLACK_CONSCIOUSNESS_DAY, '2026-11-20', $official];

        yield 'Corpus Christi' => [2026, HolidayName::CORPUS_CHRISTI, '2026-06-04', HolidayType::RELIGIOUS | HolidayType::PARTIAL_ONLY | HolidayType::DAY_OFF];

        yield 'Christmas' => [2026, HolidayName::CHRISTMAS_DAY, '2026-12-25', $official | HolidayType::RELIGIOUS];

        yield 'secular republic day' => [2026, HolidayName::REPUBLIC_PROCLAMATION_DAY, '2026-11-15', $official];

        yield 'postponed first round' => [2020, HolidayName::ELECTORAL_DAY_ROUND_ONE, '2020-11-15', $official];

        yield 'postponed second round' => [2020, HolidayName::ELECTORAL_DAY_ROUND_TWO, '2020-11-29', $official];

        yield 'regular first round' => [2026, HolidayName::ELECTORAL_DAY_ROUND_ONE, '2026-10-04', $official];

        yield 'regular second round' => [2026, HolidayName::ELECTORAL_DAY_ROUND_TWO, '2026-10-25', $official];
    }
}
