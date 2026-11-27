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

namespace Umulmrum\Holiday\Provider\Australia;

use DateTimeImmutable;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Compensatory\CompensatoryDaysCalculator;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\CompensatoryHolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Australia implements CompensatoryHolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::DAY_OFF));
        if ($year >= 1946) {
            $holidays->add($this->getAustraliaDay($year, HolidayType::DAY_OFF));
        }
        $holidays->add($this->getGoodFriday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getEasterMonday($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        if ($year >= 1927) {
            $holidays->add($this->getAnzacDay($year, HolidayType::DAY_OFF));
        }
        if ($year >= 1937) {
            $holidays->add($this->getKingsBirthday($year, HolidayType::DAY_OFF));
        }
        if ($year === 2022) {
            $holidays->add($this->getNationalDayOfMourningElizabethII(HolidayType::DAY_OFF));
        }
        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getBoxingDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));

        return $holidays;
    }

    protected function getAustraliaDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::AUSTRALIA_DAY, sprintf('%04d-01-26', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getAnzacDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::ANZAC_DAY, sprintf('%04d-04-25', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getKingsBirthday(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(HolidayName::KINGS_BIRTHDAY, new DateTimeImmutable(sprintf('Second Monday of %04d-06', $year)), HolidayType::OFFICIAL | $additionalType);
    }

    private function getNationalDayOfMourningElizabethII(int $additionalType): Holiday
    {
        return Holiday::create(HolidayName::NATIONAL_DAY_OF_MOURNING, '2022-09-22', HolidayType::OFFICIAL | $additionalType);
    }

    public function getCompensatoryDaysCalculators(int $year): array
    {
        return [
            new CompensatoryDaysCalculator(
                [
                    HolidayName::NEW_YEAR,
                    HolidayName::AUSTRALIA_DAY,
                    HolidayName::CHRISTMAS_DAY,
                    HolidayName::BOXING_DAY,
                ],
            ),
        ];
    }
}
