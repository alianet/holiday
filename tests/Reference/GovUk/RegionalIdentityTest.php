<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test\Reference\GovUk;

use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\HolidayCalculator;

final class RegionalIdentityTest extends TestCase
{
    public function testHistoricalProclamations(): void
    {
        $calculator = new HolidayCalculator();
        foreach (['GB', 'GB-ENG', 'GB-WLS', 'GB-NIR'] as $code) {
            $holidays = $calculator->calculate($code, 2002);
            self::assertSame('2002-06-03', $holidays->getByName('golden_jubilee_bank_holiday')[0]->getSimpleDate());
            self::assertSame('2002-06-04', $holidays->getByName('spring_bank_holiday')[0]->getSimpleDate());
        }
        self::assertSame('1977-06-07', $calculator->calculate('GB-NIR', 1977)->getByName('silver_jubilee_bank_holiday')[0]->getSimpleDate());
        self::assertSame('1977-06-06', $calculator->calculate('GB-SCT', 1977)->getByName('silver_jubilee_bank_holiday')[0]->getSimpleDate());
        self::assertSame([], $calculator->calculate('GB-SCT', 1977)->getByName('spring_bank_holiday'));
        self::assertSame('1978-05-29', $calculator->calculate('GB-SCT', 1978)->getByName('spring_bank_holiday')[0]->getSimpleDate());
    }

    public function testEnglandAndWalesRetainTheCompleteBaseCalendar(): void
    {
        $calculator = new HolidayCalculator();
        for ($year = 2019; $year <= 2028; ++$year) {
            $base = $calculator->calculate('GB', $year)->getList();
            self::assertEquals($base, $calculator->calculate('GB-ENG', $year)->getList());
            self::assertEquals($base, $calculator->calculate('GB-WLS', $year)->getList());
        }
    }
}
