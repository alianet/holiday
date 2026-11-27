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

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use Umulmrum\Holiday\HolidayCalculator;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class HistoricalStateTest extends HolidayTestCase
{
    /** @param list<string> $expectedDates */
    #[DataProvider('provideHistoricalHolidays')]
    public function testHistoricalHoliday(string $code, int $year, string $name, array $expectedDates): void
    {
        $dates = [];
        foreach ((new HolidayCalculator())->calculate($code, $year)->getByName($name) as $holiday) {
            $dates[] = $holiday->getSimpleDate();
        }
        self::assertSame($expectedDates, $dates);
    }

    /**
     * Historical regression expectations, separate from the 2020–2026 snapshot.
     * These exercise the ported rules, not independent verification of state law.
     *
     * @return Generator<string, array{string, int, string, list<string>}>
     */
    public static function provideHistoricalHolidays(): Generator
    {
        yield 'US-AK 1958 alaska_day' => ['US-AK', 1958, 'alaska_day', []];

        yield 'US-AK 1959 alaska_day' => ['US-AK', 1959, 'alaska_day', ['1959-10-18']];

        yield 'US-AK 1989 lincolns_birthday' => ['US-AK', 1989, 'lincolns_birthday', ['1989-02-12']];

        yield 'US-AK 1990 lincolns_birthday' => ['US-AK', 1990, 'lincolns_birthday', []];

        yield 'US-AK 1989 martin_luther_king_jr_day' => ['US-AK', 1989, 'martin_luther_king_jr_day', []];

        yield 'US-AK 1990 martin_luther_king_jr_day' => ['US-AK', 1990, 'martin_luther_king_jr_day', ['1990-01-15']];

        yield 'US-CA 1970 lincolns_birthday' => ['US-CA', 1970, 'lincolns_birthday', []];

        yield 'US-CA 1971 lincolns_birthday' => ['US-CA', 1971, 'lincolns_birthday', ['1971-02-12']];

        yield 'US-CA 2009 lincolns_birthday' => ['US-CA', 2009, 'lincolns_birthday', ['2009-02-12']];

        yield 'US-CA 2010 lincolns_birthday' => ['US-CA', 2010, 'lincolns_birthday', []];

        yield 'US-CA 2008 columbus_day' => ['US-CA', 2008, 'columbus_day', ['2008-10-13']];

        yield 'US-CA 2009 columbus_day' => ['US-CA', 2009, 'columbus_day', []];

        yield 'US-DE 1989 washingtons_birthday' => ['US-DE', 1989, 'washingtons_birthday', ['1989-02-20']];

        yield 'US-DE 1990 washingtons_birthday' => ['US-DE', 1990, 'washingtons_birthday', []];

        yield 'US-DE 1990 presidents_day' => ['US-DE', 1990, 'presidents_day', ['1990-02-19']];

        yield 'US-DE 2009 presidents_day' => ['US-DE', 2009, 'presidents_day', ['2009-02-16']];

        yield 'US-DE 2010 presidents_day' => ['US-DE', 2010, 'presidents_day', []];

        yield 'US-GA 2015 confederate_memorial_day' => ['US-GA', 2015, 'confederate_memorial_day', ['2015-04-27']];

        yield 'US-GA 2016 confederate_memorial_day' => ['US-GA', 2016, 'confederate_memorial_day', []];

        yield 'US-GA 2019 state_holiday' => ['US-GA', 2019, 'state_holiday', ['2019-04-22', '2019-11-29']];

        yield 'US-GA 2020 state_holiday' => ['US-GA', 2020, 'state_holiday', ['2020-04-10', '2020-11-27']];

        yield 'US-ME 1968 patriots_day' => ['US-ME', 1968, 'patriots_day', ['1968-04-19']];

        yield 'US-ME 1969 patriots_day' => ['US-ME', 1969, 'patriots_day', ['1969-04-21']];

        yield 'US-MA 1968 patriots_day' => ['US-MA', 1968, 'patriots_day', ['1968-04-19']];

        yield 'US-MA 1969 patriots_day' => ['US-MA', 1969, 'patriots_day', ['1969-04-21']];

        yield 'US-NE 1988 arbor_day' => ['US-NE', 1988, 'arbor_day', ['1988-04-22']];

        yield 'US-NE 1989 arbor_day' => ['US-NE', 1989, 'arbor_day', ['1989-04-28']];

        yield 'US-SD 1969 columbus_day' => ['US-SD', 1969, 'columbus_day', ['1969-10-12']];

        yield 'US-SD 1970 columbus_day' => ['US-SD', 1970, 'columbus_day', ['1970-10-12']];

        yield 'US-SD 1990 columbus_day' => ['US-SD', 1990, 'columbus_day', []];

        yield 'US-SD 1990 native_americans_day' => ['US-SD', 1990, 'native_americans_day', ['1990-10-08']];

        yield 'US-VA 1982 lee_jackson_day' => ['US-VA', 1982, 'lee_jackson_day', ['1982-01-19']];

        yield 'US-VA 1983 lee_jackson_day' => ['US-VA', 1983, 'lee_jackson_day', ['1983-01-17']];

        yield 'US-VA 1999 lee_jackson_day' => ['US-VA', 1999, 'lee_jackson_day', ['1999-01-18']];

        yield 'US-VA 2000 lee_jackson_day' => ['US-VA', 2000, 'lee_jackson_day', ['2000-01-14']];

        yield 'US-VA 2020 lee_jackson_day' => ['US-VA', 2020, 'lee_jackson_day', ['2020-01-17']];

        yield 'US-VA 2021 lee_jackson_day' => ['US-VA', 2021, 'lee_jackson_day', []];

        yield 'US-MA 1970 memorial_day' => ['US-MA', 1970, 'memorial_day', ['1970-05-30']];

        yield 'US-MA 1971 memorial_day' => ['US-MA', 1971, 'memorial_day', ['1971-05-31']];

        yield 'US-MA 1970 washingtons_birthday' => ['US-MA', 1970, 'washingtons_birthday', ['1970-02-22']];

        yield 'US-MA 1971 washingtons_birthday' => ['US-MA', 1971, 'washingtons_birthday', ['1971-02-15']];

        yield 'US-MA 1970 columbus_day' => ['US-MA', 1970, 'columbus_day', ['1970-10-12']];

        yield 'US-MA 1971 columbus_day' => ['US-MA', 1971, 'columbus_day', ['1971-10-11']];

        yield 'US-MA 1970 veterans_day' => ['US-MA', 1970, 'veterans_day', ['1970-11-11']];

        yield 'US-MA 1971 veterans_day' => ['US-MA', 1971, 'veterans_day', ['1971-10-25']];

        yield 'US-MA 1977 veterans_day' => ['US-MA', 1977, 'veterans_day', ['1977-10-24']];

        yield 'US-MA 1978 veterans_day' => ['US-MA', 1978, 'veterans_day', ['1978-11-11']];

        yield 'US-MA 1862 thanksgiving_day' => ['US-MA', 1862, 'thanksgiving_day', ['1862-04-10']];

        yield 'US-MA 1865 thanksgiving_day' => ['US-MA', 1865, 'thanksgiving_day', ['1865-12-07']];

        yield 'US-MA 1869 thanksgiving_day' => ['US-MA', 1869, 'thanksgiving_day', ['1869-11-18']];

        yield 'US-MA 1938 thanksgiving_day' => ['US-MA', 1938, 'thanksgiving_day', ['1938-11-24']];

        yield 'US-MA 1939 thanksgiving_day' => ['US-MA', 1939, 'thanksgiving_day', ['1939-11-23']];

        yield 'US-MA 1940 thanksgiving_day' => ['US-MA', 1940, 'thanksgiving_day', ['1940-11-21']];

        yield 'US-MA 1941 thanksgiving_day' => ['US-MA', 1941, 'thanksgiving_day', ['1941-11-20']];

        yield 'US-MA 1942 thanksgiving_day' => ['US-MA', 1942, 'thanksgiving_day', ['1942-11-26']];
    }
}
