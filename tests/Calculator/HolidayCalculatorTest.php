<?php

declare(strict_types=1);

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Calculator;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use stdClass;
use Throwable;
use TypeError;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\Belgium\Belgium;
use Umulmrum\Holiday\Provider\Germany\Berlin;
use Umulmrum\Holiday\Provider\Germany\Brandenburg;
use Umulmrum\Holiday\Provider\Germany\Germany;
use Umulmrum\Holiday\Provider\Germany\Hesse;
use Umulmrum\Holiday\Provider\Germany\Saxony;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Resolver\ResolverHandlerInterface;
use Umulmrum\Holiday\Test\HolidayTestCase;
use Umulmrum\Holiday\Test\ResolverHandlerStub;

use function array_map;

final class HolidayCalculatorTest extends HolidayTestCase
{
    private HolidayCalculator $holidayCalculator;
    private HolidayList $actualResult;

    /**
     * @param array<HolidayProviderInterface|string>|HolidayProviderInterface|string $holidayProviders
     * @param int|int[]                                                              $years
     */
    #[DataProvider('provideDataForTestDefaultResolver')]
    #[Test]
    public function itCalculatesWithDefaultResolver(array|HolidayProviderInterface|string $holidayProviders, array|int $years): void
    {
        $this->givenHolidayCalculator();
        $this->whenCalculateIsCalled($holidayProviders, $years);
        $this->thenNoExceptionShouldBeThrown();
    }

    public static function provideDataForTestDefaultResolver(): Generator
    {
        yield [
            Germany::class,
            2020,
        ];

        yield [
            new Hesse(),
            [2020, 2021],
        ];

        yield [
            [
                new Germany(),
                Brandenburg::class,
                Berlin::class,
                new Saxony(),
            ],
            [2020],
        ];

        yield [
            'DE',
            2020,
        ];
    }

    private function givenHolidayCalculator(?ResolverHandlerInterface $resolverHandler = null): void
    {
        $this->holidayCalculator = new HolidayCalculator($resolverHandler);
    }

    /**
     * @param HolidayProviderInterface|HolidayProviderInterface[]|string|string[] $holidayProviders
     * @param int|int[]                                                           $years
     */
    private function whenCalculateIsCalled($holidayProviders, array|int $years): void
    {
        $this->actualResult = $this->holidayCalculator->calculate($holidayProviders, $years);
    }

    private function thenNoExceptionShouldBeThrown(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /** @param class-string<Throwable> $exception */
    #[DataProvider('provideDataForTestThrowExceptionOnInvalidArgument')]
    #[Test]
    public function itThrowsExceptionOnInvalidArguments(mixed $holidayProviders, mixed $years, string $exception): void
    {
        $this->givenHolidayCalculator();
        $this->expectException($exception);
        // @phpstan-ignore argument.type, argument.type (Intentionally invalid input to test exception handling.)
        $this->holidayCalculator->calculate($holidayProviders, $years);
    }

    public static function provideDataForTestThrowExceptionOnInvalidArgument(): Generator
    {
        yield 'null provider' => [null, 2020, TypeError::class];

        yield 'integer provider' => [1, 2020, TypeError::class];

        yield 'unknown class' => ['no class name', 2020, InvalidArgumentException::class];

        yield 'class is not a provider' => [HolidayCalculator::class, 2020, InvalidArgumentException::class];

        yield 'object is not a provider' => [new stdClass(), 2020, TypeError::class];

        yield 'invalid provider in array' => [[Germany::class, new stdClass()], 2020, InvalidArgumentException::class];

        yield 'string year' => [Belgium::class, 'string', TypeError::class];

        yield 'numeric string year' => [Belgium::class, '2020', TypeError::class];

        yield 'null year' => [Belgium::class, null, TypeError::class];

        yield 'boolean year' => [Belgium::class, false, TypeError::class];

        yield 'invalid year in array' => [Belgium::class, [2020, 'foo'], InvalidArgumentException::class];

        yield 'numeric string in year array' => [Belgium::class, [2020, '2021'], InvalidArgumentException::class];

        yield 'year below range' => [Belgium::class, 0, InvalidArgumentException::class];

        yield 'year above range' => [Belgium::class, 10000, InvalidArgumentException::class];
    }

    #[Test]
    public function itUsesPassedResolverHandler(): void
    {
        $this->givenHolidayCalculator(new ResolverHandlerStub());
        $this->whenCalculateIsCalled('x', 2020);
        $this->thenExpectedHolidaysShouldBeReturned(['2020-01-02', '2020-07-07']);
    }

    /**
     * @param string[] $expectedHolidays
     */
    private function thenExpectedHolidaysShouldBeReturned(array $expectedHolidays): void
    {
        self::assertEquals($expectedHolidays, array_map(static fn (Holiday $holiday) => $holiday->getSimpleDate(), $this->actualResult->getList()));
    }
}
