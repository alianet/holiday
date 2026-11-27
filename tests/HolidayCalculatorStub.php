<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Test;

use Umulmrum\Holiday\HolidayCalculatorInterface;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

final readonly class HolidayCalculatorStub implements HolidayCalculatorInterface
{
    public function __construct(private HolidayList $holidayList) {}

    public function calculate(array|HolidayProviderInterface|string $holidayProviders, array|int $years): HolidayList
    {
        return $this->holidayList;
    }
}
