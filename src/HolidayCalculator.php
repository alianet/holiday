<?php

declare(strict_types=1);

/*
 * This file is part of the alianet/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday;

use InvalidArgumentException;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Resolver\ClassNameResolver;
use Umulmrum\Holiday\Resolver\IsoResolver;
use Umulmrum\Holiday\Resolver\MiscResolver;
use Umulmrum\Holiday\Resolver\ResolverHandler;
use Umulmrum\Holiday\Resolver\ResolverHandlerInterface;

use function is_int;
use function print_r;

final class HolidayCalculator implements HolidayCalculatorInterface
{
    private ResolverHandlerInterface $resolverHandler;

    public function __construct(?ResolverHandlerInterface $resolverHandler = null)
    {
        $this->resolverHandler = $resolverHandler ?? new ResolverHandler([new ClassNameResolver(), new IsoResolver(), new MiscResolver()]);
    }

    public function calculate(array|HolidayProviderInterface|string $holidayProviders, array|int $years): HolidayList
    {
        $finalYears = $this->interpretYears($years);
        foreach ($finalYears as $year) {
            Year::validate($year);
        }

        $holidays = new HolidayList();
        foreach ($this->resolverHandler->resolve($holidayProviders) as $holidayProvider) {
            foreach ($finalYears as $year) {
                $subHolidays = $holidayProvider->calculateHolidaysForYear($year);
                if ($holidayProvider instanceof CompensatoryHolidayProviderInterface) {
                    foreach ($holidayProvider->getCompensatoryDaysCalculators($year) as $strategy) {
                        $strategy->addAll($subHolidays, $holidayProvider, $year);
                    }
                }
                $holidays->addAll($subHolidays);
            }
        }

        return $holidays;
    }

    /**
     * @param int|int[] $years
     *
     * @return int[]
     *
     * @throws InvalidArgumentException
     */
    private function interpretYears(array|int $years): array
    {
        if (is_int($years)) {
            return [$years];
        }

        foreach ($years as $year) {
            if (!is_int($year)) {
                throw new InvalidArgumentException('array of int expected, got: ' . print_r($years, true));
            }
        }

        return $years;
    }
}
