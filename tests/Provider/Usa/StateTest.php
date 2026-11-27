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

namespace Umulmrum\Holiday\Test\Provider\Usa;

use InvalidArgumentException;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Provider\Usa\California;
use Umulmrum\Holiday\Provider\Usa\Texas;
use Umulmrum\Holiday\Test\HolidayTestCase;
use Umulmrum\Holiday\Translator\Translator;

final class StateTest extends HolidayTestCase
{
    public function testFederalAndStateUnionDeduplicatesAndPreservesBothCalendars(): void
    {
        $calculator = new HolidayCalculator();
        $state = $calculator->calculate('US-CA', 2024);
        self::assertEmpty($state->getByName(HolidayName::COLUMBUS_DAY));
        self::assertCount(1, $state->getByName(HolidayName::CESAR_CHAVEZ_DAY));
        $union = $calculator->calculate(['US', 'US-CA'], 2024);
        self::assertCount(1, $union->getByName(HolidayName::COLUMBUS_DAY));
        self::assertCount(1, $union->getByName(HolidayName::INDEPENDENCE_DAY));
        self::assertCount(1, $union->getByName(HolidayName::CESAR_CHAVEZ_DAY));
        self::assertCount(1, $calculator->calculate(['US-CA', 'US-CA'], 2024)->getByName(HolidayName::CESAR_CHAVEZ_DAY));
    }

    public function testStateSpecificObservedRulesAndClassification(): void
    {
        $calculator = new HolidayCalculator();
        $california = $calculator->calculate('US-CA', 2024);
        $observed = $california->getByName(HolidayName::CESAR_CHAVEZ_DAY . HolidayName::SUFFIX_COMPENSATORY);
        self::assertCount(1, $observed);
        self::assertSame('2024-04-01', $observed[0]->getSimpleDate());
        self::assertTrue($observed[0]->hasType(HolidayType::COMPENSATORY));
        self::assertFalse($observed[0]->hasType(HolidayType::GOVERNMENT_AGENCIES_CLOSED));
        self::assertTrue($observed[0]->hasType(HolidayType::PARTIAL_ONLY));
        self::assertFalse($observed[0]->hasType(HolidayType::DAY_OFF));
        // Massachusetts does not move Saturday Christmas to Friday.
        self::assertEmpty($calculator->calculate('US-MA', 2021)->getByName(HolidayName::CHRISTMAS_DAY_COMPENSATORY));
        self::assertSame('2021-12-31', $calculator->calculate('US-NY', 2021)->getByName(HolidayName::NEW_YEAR_COMPENSATORY)[0]->getSimpleDate());
    }

    public function testDirectProviderOmitsCompensatoryDatesAndCanBeReused(): void
    {
        $provider = new California();
        $first = $provider->calculateHolidaysForYear(2024);
        self::assertEmpty($first->getByName(HolidayName::CESAR_CHAVEZ_DAY . HolidayName::SUFFIX_COMPENSATORY));
        $provider->calculateHolidaysForYear(2025);
        self::assertEquals($first, $provider->calculateHolidaysForYear(2024));
    }

    public function testElectionAndInaugurationCycles(): void
    {
        $calculator = new HolidayCalculator();
        self::assertCount(1, $calculator->calculate('US-DE', 2024)->getByName(HolidayName::ELECTION_DAY));
        self::assertEmpty($calculator->calculate('US-DE', 2025)->getByName(HolidayName::ELECTION_DAY));
        self::assertCount(1, $calculator->calculate('US-DC', 2025)->getByName(HolidayName::INAUGURATION_DAY));
        self::assertEmpty($calculator->calculate('US-DC', 2026)->getByName(HolidayName::INAUGURATION_DAY));
    }

    public function testBoundaryYears(): void
    {
        $calculator = new HolidayCalculator();
        self::assertCount(0, $calculator->calculate(Texas::class, 1));
        self::assertGreaterThan(0, $calculator->calculate(Texas::class, 9999)->count());
        self::assertCount(1, $calculator->calculate(California::class, 2100)->getByName(HolidayName::DIWALI));
    }

    public function testUnavailableDiwaliYearFailsExplicitly(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Diwali');
        (new California())->calculateHolidaysForYear(2101);
    }

    public function testInvalidYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new Texas())->calculateHolidaysForYear(0);
    }

    public function testEnglishNamesAreAvailableIncludingSubstituteDays(): void
    {
        $translator = new Translator();
        foreach ((new HolidayCalculator())->calculate(['US-TX', 'US-NC', 'US-AK', 'US-WI'], [2021, 2022, 2023]) as $holiday) {
            self::assertNotSame('', $translator->translateName($holiday, 'en'), $holiday->getName());
        }
    }
}
