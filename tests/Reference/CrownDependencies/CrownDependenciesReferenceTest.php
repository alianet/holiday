<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Reference\CrownDependencies;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Guernsey\Guernsey;
use Umulmrum\Holiday\Provider\IsleOfMan\IsleOfMan;
use Umulmrum\Holiday\Provider\Jersey\Jersey;
use Umulmrum\Holiday\Resolver\IsoResolver;

final class CrownDependenciesReferenceTest extends TestCase
{
    public function testObservedDates(): void
    {
        /** @var array<string, array<int, list<string>>> $data */
        $data = json_decode((string) file_get_contents(__DIR__ . '/observed-dates.json'), true, 512, JSON_THROW_ON_ERROR);
        foreach ($data as $code => $years) {
            foreach ($years as $year => $dates) {
                $actual = [];
                foreach ((new HolidayCalculator())->calculate($code, $year) as $holiday) {
                    self::assertFalse($holiday->hasType(HolidayType::DAY_OFF));
                    // Original weekend dates are retained as traditional entries.
                    if (!$holiday->hasType(HolidayType::BANK) || (!$holiday->hasType(HolidayType::OFFICIAL) && $holiday->hasType(HolidayType::TRADITIONAL) && (int) $holiday->getDate()->format('N') > 5)) {
                        continue;
                    }
                    $actual[] = substr($holiday->getSimpleDate(), 5);
                }
                sort($actual);
                self::assertSame($dates, $actual, "{$code} {$year}");
            }
        }
    }

    public function testSeparateIsoIdentitiesAndBoundaries(): void
    {
        foreach (['JE' => Jersey::class, 'GG' => Guernsey::class, 'IM' => IsleOfMan::class] as $code => $class) {
            self::assertInstanceOf($class, (new IsoResolver())->resolveHolidayProvider($code));
            foreach ([2019, 2028] as $year) {
                try {
                    (new $class())->calculateHolidaysForYear($year);
                    self::fail('Expected unsupported year rejection');
                } catch (InvalidArgumentException $exception) {
                    self::assertStringContainsString('2020–2027', $exception->getMessage());
                }
            }
        }
    }

    public function testLiberationDoesNotAcquireSubstitutes(): void
    {
        $calculator = new HolidayCalculator();
        foreach (['JE', 'GG'] as $code) {
            $days = $calculator->calculate($code, 2027)->getByName('liberation_day');
            self::assertCount(1, $days);
            self::assertSame('2027-05-09', $days[0]->getSimpleDate());
            self::assertFalse($days[0]->hasType(HolidayType::COMPENSATORY));
            self::assertSame($code === 'GG', $days[0]->hasType(HolidayType::OFFICIAL));
        }
    }
}
