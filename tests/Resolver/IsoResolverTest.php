<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Resolver;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Umulmrum\Holiday\Provider\Belgium\Belgium;
use Umulmrum\Holiday\Provider\France\BasRhin;
use Umulmrum\Holiday\Provider\Germany\BadenWuerttemberg;
use Umulmrum\Holiday\Provider\Germany\Germany;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Italy\Italy;
use Umulmrum\Holiday\Resolver\IsoResolver;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class IsoResolverTest extends HolidayTestCase
{
    private IsoResolver $subject;
    private ?HolidayProviderInterface $actualResult;

    #[DataProvider('provideDataForResolveProviders')]
    #[Test]
    public function itShouldResolveProviders(string $identifier, ?HolidayProviderInterface $expectedResult = null): void
    {
        $this->givenIsoResolver();
        $this->whenResolveHolidayProviderIsCalled($identifier);
        $this->thenTheExpectedResultShouldBeReturned($expectedResult);
    }

    public static function provideDataForResolveProviders(): \Generator
    {
        yield 'country' => [
            'DE',
            new Germany(),
        ];
        yield 'wrong-case' => [
            'de',
            null,
        ];
        yield 'region' => [
            'DE-BW',
            new BadenWuerttemberg(),
        ];
        yield 'nonexisting' => [
            'foo',
            null,
        ];
        yield 'toolong' => [
            '12345678',
            null,
        ];
        yield 'not-implemented-region' => [
            'IT-65',
            new Italy(),
        ];
        yield 'nonexisting-region' => [
            'IT-foo',
            new Italy(),
        ];
    }

    #[Test]
    public function itShouldResolveMultipleProvidersConsecutively(): void
    {
        $this->givenIsoResolver();
        $this->whenResolveHolidayProviderIsCalled('FR-67');
        $this->thenTheExpectedResultShouldBeReturned(new BasRhin());

        $this->whenResolveHolidayProviderIsCalled('BE');
        $this->thenTheExpectedResultShouldBeReturned(new Belgium());
    }

    private function givenIsoResolver(): void
    {
        $this->subject = new IsoResolver();
    }

    private function whenResolveHolidayProviderIsCalled(string $identifier): void
    {
        $this->actualResult = $this->subject->resolveHolidayProvider($identifier);
    }

    private function thenTheExpectedResultShouldBeReturned(?HolidayProviderInterface $expectedResult): void
    {
        self::assertEquals($expectedResult, $this->actualResult);
    }
}
