<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Reference\Japan;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;

final class JapanReferenceTest extends TestCase
{
    /** @return array<int, array{date: string, name: string}> */
    private static function records(): array
    {
        /** @var array<int, array{date: string, name: string}> $records */
        $records = json_decode((string) file_get_contents(__DIR__ . '/Data/holidays.json'), true, 512, JSON_THROW_ON_ERROR);

        return $records;
    }

    #[DataProvider('years')]
    public function testOfficialCalendar(int $year): void
    {
        $names = [
            '元日' => HolidayName::NEW_YEAR,
            '成人の日' => HolidayName::COMING_OF_AGE_DAY,
            '建国記念の日' => HolidayName::JAPAN_FOUNDATION_DAY,
            '天皇誕生日' => HolidayName::EMPERORS_BIRTHDAY,
            '春分の日' => HolidayName::VERNAL_EQUINOX_DAY,
            'みどりの日' => HolidayName::GREENERY_DAY,
            '昭和の日' => HolidayName::SHOWA_DAY,
            '憲法記念日' => HolidayName::JAPAN_CONSTITUTION_DAY,
            'こどもの日' => HolidayName::JAPAN_CHILDRENS_DAY,
            '海の日' => HolidayName::MARINE_DAY,
            '山の日' => HolidayName::MOUNTAIN_DAY,
            '敬老の日' => HolidayName::RESPECT_FOR_THE_AGED_DAY,
            '秋分の日' => HolidayName::AUTUMNAL_EQUINOX_DAY,
            '体育の日' => HolidayName::SPORTS_DAY,
            '体育の日（スポーツの日）' => HolidayName::SPORTS_DAY,
            'スポーツの日' => HolidayName::SPORTS_DAY,
            '文化の日' => HolidayName::CULTURE_DAY,
            '勤労感謝の日' => HolidayName::LABOR_THANKSGIVING_DAY,
            '大喪の礼' => HolidayName::STATE_FUNERAL_OF_EMPEROR_SHOWA,
            '即位礼正殿の儀' => HolidayName::ENTHRONEMENT_CEREMONY_DAY,
            '結婚の儀' => HolidayName::ROYAL_WEDDING,
            '休日（祝日扱い）' => HolidayName::ENTHRONEMENT_DAY,
        ];
        $expectedDates = [];
        $actual = [];
        foreach ((new HolidayCalculator())->calculate('JP', $year) as $holiday) {
            $actual[$holiday->getSimpleDate()] = $holiday;
            self::assertTrue($holiday->hasType(HolidayType::OFFICIAL));
            self::assertTrue($holiday->hasType(HolidayType::DAY_OFF));
        }
        foreach (self::records() as $record) {
            if ((int) substr($record['date'], 0, 4) !== $year) {
                continue;
            }
            $date = $record['date'];
            $expectedDates[] = $date;
            self::assertArrayHasKey($date, $actual);
            $holiday = $actual[$date];
            if ($record['name'] === '休日') {
                // The official feed does not distinguish substitutes from bridge days.
                self::assertTrue($holiday->hasType(HolidayType::COMPENSATORY) || $holiday->getName() === HolidayName::CITIZENS_HOLIDAY);
            } else {
                self::assertArrayHasKey($record['name'], $names, 'Unknown official holiday title');
                $expectedName = $record['name'] === '休日（祝日扱い）' && $date === '2019-10-22'
                    ? HolidayName::ENTHRONEMENT_CEREMONY_DAY
                    : $names[$record['name']];
                self::assertSame($expectedName, $holiday->getName(), $date);
                self::assertFalse($holiday->hasType(HolidayType::COMPENSATORY));
            }
        }
        self::assertNotEmpty($expectedDates);
        $actualDates = array_keys($actual);
        sort($expectedDates);
        sort($actualDates);
        self::assertSame($expectedDates, $actualDates);
        self::assertCount(count($actualDates), (new HolidayCalculator())->calculate('JP', $year));
    }

    /** @return iterable<string, array{int}> */
    public static function years(): iterable
    {
        // Fixed minimum coverage: a truncated or missing snapshot must fail.
        foreach (range(1980, 2027) as $year) {
            yield (string) $year => [$year];
        }
    }

    public function testSnapshotIntegrity(): void
    {
        /** @var array{sha256: string, years: list<int>} $manifest */
        $manifest = json_decode((string) file_get_contents(__DIR__ . '/source.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame($manifest['sha256'], hash_file('sha256', __DIR__ . '/Data/holidays.json'));
        $years = array_values(array_unique(array_map(static fn (array $record): int => (int) substr($record['date'], 0, 4), self::records())));
        self::assertSame(range(1980, 2027), $years);
        self::assertSame($manifest['years'], $years);
    }
}
