<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Reference\Ireland;

use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;

final class IrelandReferenceTest extends TestCase
{
    public function testPublicHolidayDates(): void
    {
        /** @var array<int, list<string>> $data */
        $data = json_decode((string) file_get_contents(__DIR__ . '/public-holidays.json'), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data as $year => $dates) {
            $expected = array_map(static fn (string $date): string => $year . '-' . $date, $dates);
            $actual = [];
            foreach ((new HolidayCalculator())->calculate('IE', (int) $year) as $holiday) {
                self::assertFalse($holiday->hasType(HolidayType::COMPENSATORY));
                if ($holiday->hasType(HolidayType::DAY_OFF)) {
                    $actual[] = $holiday->getSimpleDate();
                } else {
                    self::assertSame('good_friday', $holiday->getName());
                    self::assertFalse($holiday->hasType(HolidayType::OFFICIAL));
                }
            }
            sort($expected);
            sort($actual);
            self::assertSame($expected, $actual, (string) $year);
        }
    }
}
