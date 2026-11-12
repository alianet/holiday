<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Translator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Test\HolidayTestCase;
use Umulmrum\Holiday\Translator\Translator;

use function implode;

final class TranslatorTest extends HolidayTestCase
{
    private Translator $translator;
    private string $actualResult;

    /** @var string[] */
    private array $notFoundTranslations;

    #[DataProvider('getTranslateNameData')]
    #[Test]
    public function itShouldTranslate(string $key, ?string $locale, string $expectedResult): void
    {
        $this->givenATranslator();
        $this->whenTranslateNameIsCalled($key, $locale);
        $this->thenTheTranslatedStringShouldBeReturned($expectedResult);
    }

    public static function getTranslateNameData(): \Generator
    {
        yield [
            'sunday',
            'en',
            'Sunday',
        ];
        yield [
            'sunday',
            'en_US',
            'Sunday',
        ];
        yield [
            'sunday',
            'de',
            'Sonntag',
        ];
        yield [
            'sunday',
            'unknown',
            'Sunday',
        ];
        yield [
            'sunday',
            null,
            'Sunday',
        ];
        yield [
            'unknown',
            'en',
            '',
        ];
        yield [
            'unknown',
            'en_US',
            '',
        ];
    }

    private function givenATranslator(): void
    {
        $this->translator = new Translator('en');
    }

    private function whenTranslateNameIsCalled(string $name, ?string $locale): void
    {
        $this->actualResult = $this->translator->translateName(Holiday::create($name, '2016-01-01'), $locale);
    }

    private function thenTheTranslatedStringShouldBeReturned(string $expectedResult): void
    {
        self::assertEquals($expectedResult, $this->actualResult);
    }

    #[Test]
    public function itShouldBeAbleToTranslateAllHolidayNames(): void
    {
        $this->givenATranslator();
        $this->whenTranslateIsCalledForAllKnownHolidayNames();
        $this->thenAllTranslationsShouldBeFoundAndNotEmpty();
    }

    private function whenTranslateIsCalledForAllKnownHolidayNames(): void
    {
        $holidayNameClass = new ReflectionClass(HolidayName::class);
        $this->notFoundTranslations = [];
        foreach ($holidayNameClass->getConstants() as $name => $value) {
            if ($name === 'SUFFIX_COMPENSATORY') {
                continue;
            }
            self::assertIsString($value);
            if ($value === $this->translator->translate($value, 'en')) {
                $this->notFoundTranslations[] = $value;
            }
        }
    }

    private function thenAllTranslationsShouldBeFoundAndNotEmpty(): void
    {
        self::assertEmpty(
            $this->notFoundTranslations,
            'Translations for the following holiday names not found. Please add them to res/trans/umulmrum_holiday.en.php: '
            . implode(', ', $this->notFoundTranslations)
        );
    }
}
