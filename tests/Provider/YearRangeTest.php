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

use function in_array;
use function range;
use function sprintf;
use function str_starts_with;

final class YearRangeTest extends TestCase
{
    #[DataProvider('provideProviders')]
    public function testSupportedYears(string $code): void
    {
        $resolver = new ResolverHandler([new IsoResolver(), new MiscResolver()]);
        [$firstSupportedYear, $lastSupportedYear] = self::supportedRange($code);
        $years = $firstSupportedYear === 1
            ? [1, 9, 99, 999, 1000, $lastSupportedYear]
            : range($firstSupportedYear, $lastSupportedYear);
        foreach ($resolver->resolve($code) as $provider) {
            foreach ($years as $year) {
                foreach ($provider->calculateHolidaysForYear($year) as $holiday) {
                    self::assertMatchesRegularExpression('/^' . sprintf('%04d', $year) . '-\d{2}-\d{2}$/', $holiday->getSimpleDate());
                    self::assertSame($holiday->getSimpleDate(), $holiday->getDate()->format('Y-m-d'));
                }
            }
        }
    }

    #[DataProvider('provideUnsupportedYears')]
    public function testProvidersRejectUnsupportedYears(string $code, int $year): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new HolidayCalculator())->calculate($code, $year);
    }

    /** @return Generator<string, array{string, int}> */
    public static function provideUnsupportedYears(): Generator
    {
        foreach (self::provideProviders() as [$code]) {
            [$first, $last] = self::supportedRange($code);
            if ($first > 1) {
                yield $code . ' before range' => [$code, $first - 1];
            }
            if ($last < 9999) {
                yield $code . ' after range' => [$code, $last + 1];
            }
        }
    }

    /** @return array{int, int} */
    private static function supportedRange(string $code): array
    {
        return match (true) {
            in_array($code, ['JE', 'GG', 'IM'], true) => [2020, 2027],
            $code === 'JP' => [1980, 2099],
            str_starts_with($code, 'ES-') => [2008, 2027],
            // California uses a finite Diwali date table.
            $code === 'US-CA' => [1, 2100],
            default => [1, 9999],
        };
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

    /** @return Generator<string, array{string}> */
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
