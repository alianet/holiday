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

namespace Umulmrum\Holiday\Test\Provider;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Resolver\IsoResolver;
use Umulmrum\Holiday\Resolver\MiscResolver;
use Umulmrum\Holiday\Resolver\ResolverHandler;

use function sprintf;

final class YearRangeTest extends TestCase
{
    #[DataProvider('provideProviders')]
    public function testSupportedYears(string $code): void
    {
        $resolver = new ResolverHandler([new IsoResolver(), new MiscResolver()]);
        // California uses a finite Diwali date table; StateTest separately
        // verifies that years beyond its supported range fail explicitly.
        $lastSupportedYear = $code === 'US-CA' ? 2100 : 9999;
        foreach ($resolver->resolve($code) as $provider) {
            foreach ([1, 9, 99, 999, 1000, $lastSupportedYear] as $year) {
                foreach ($provider->calculateHolidaysForYear($year) as $holiday) {
                    self::assertMatchesRegularExpression('/^' . sprintf('%04d', $year) . '-\d{2}-\d{2}$/', $holiday->getSimpleDate());
                    self::assertSame($holiday->getSimpleDate(), $holiday->getDate()->format('Y-m-d'));
                }
            }
        }
    }

    #[DataProvider('provideProviders')]
    public function testProvidersRejectInvalidYears(string $code): void
    {
        $resolver = new ResolverHandler([new IsoResolver(), new MiscResolver()]);
        foreach ($resolver->resolve($code) as $provider) {
            foreach ([PHP_INT_MIN, -1, 0, 10000, PHP_INT_MAX] as $year) {
                try {
                    $provider->calculateHolidaysForYear($year);
                    self::fail('Expected invalid year to be rejected.');
                } catch (InvalidArgumentException $exception) {
                    self::assertSame('Year must be between 1 and 9999, got ' . $year, $exception->getMessage());
                }
            }
        }
    }

    public static function provideProviders(): Generator
    {
        foreach (ProviderTest::provideDataForTestProviders() as $data) {
            yield $data['providerCode'] => [$data['providerCode']];
        }
    }

    public function testCalculatorValidatesYearsBeforeResolvingProviders(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Year must be between 1 and 9999, got 10000');
        (new HolidayCalculator())->calculate([], [999, 10000]);
    }
}
