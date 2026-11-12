<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Filter;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Umulmrum\Holiday\Filter\SortByTypeFilter;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class SortByTypeFilterTest extends HolidayTestCase
{
    private SortByTypeFilter $filter;
    private HolidayList $actualResult;

    /**
     * @param int[] $holidays
     * @param int[] $expectedResult
     */
    #[DataProvider('getData')]
    #[Test]
    public function itShouldFilterHolidays(array $holidays, array $expectedResult): void
    {
        $this->givenASortByTypeFilter();
        $this->whenFilterIsCalled($holidays);
        $this->thenACorrectlyFilteredResultShouldBeReturned($expectedResult);
    }

    public static function getData(): \Generator
    {
        yield [
            [
                0,
                1,
                2,
            ],
            [
                0,
                1,
                2,
            ],
        ];
        yield [
            [
                3,
                2,
                1,
            ],
            [
                1,
                2,
                3,
            ],
        ];
        yield [
            [
                0,
                0,
            ],
            [
                0,
                0,
            ],
        ];
        yield [
            [
                0,
            ],
            [
                0,
            ],
        ];
        yield [
            [
                3,
                1,
                1,
            ],
            [
                1,
                1,
                3,
            ],
        ];
        yield [
            [],
            [],
        ];
        yield [
            [
                6,
                6,
                3,
                3,
                1,
                1,
            ],
            [
                1,
                1,
                3,
                3,
                6,
                6,
            ],
        ];
    }

    private function givenASortByTypeFilter(): void
    {
        $this->filter = new SortByTypeFilter();
    }

    /**
     * @param int[] $holidays
     */
    private function whenFilterIsCalled(array $holidays): void
    {
        $holidayList = new HolidayList();
        foreach ($holidays as $index => $type) {
            $holidayList->add(Holiday::create("name{$index}", "2020-01-0{$index}", $type));
        }
        $this->actualResult = $holidayList->filter($this->filter);
    }

    /**
     * @param int[] $expectedResult
     */
    private function thenACorrectlyFilteredResultShouldBeReturned(array $expectedResult): void
    {
        $resultTypes = [];
        foreach ($this->actualResult->getList() as $result) {
            $resultTypes[] = $result->getType();
        }
        self::assertEquals($expectedResult, $resultTypes);
    }
}
