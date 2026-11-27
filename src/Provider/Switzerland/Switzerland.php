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

namespace Umulmrum\Holiday\Provider\Switzerland;

use DateInterval;
use DateTime;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\CommonHolidaysTrait;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;
use Umulmrum\Holiday\Provider\Religion\ChristianHolidaysTrait;

use function sprintf;

class Switzerland implements HolidayProviderInterface
{
    use ChristianHolidaysTrait;
    use CommonHolidaysTrait;

    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);

        $holidays = new HolidayList();
        $holidays->add($this->getNewYear($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getAscension($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));
        $holidays->add($this->getFederalDayofThanksgivingRepentanceAndPrayer($year, HolidayType::OTHER));

        if ($year >= 1994) {
            $holidays->add($this->getSwissNationalDay($year, HolidayType::DAY_OFF));
        } else {
            $holidays->add($this->getSwissNationalDay($year, HolidayType::OTHER));
        }

        $holidays->add($this->getChristmasDay($year, HolidayType::OFFICIAL | HolidayType::DAY_OFF));

        return $holidays;
    }

    protected function getBerchtoldstag(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::BERCHTOLDSTAG, sprintf('%04d-01-02', $year), HolidayType::TRADITIONAL | $additionalType);
    }

    private function getSwissNationalDay(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::create(HolidayName::SWISS_NATIONAL_DAY, sprintf('%04d-08-01', $year), HolidayType::OFFICIAL | $additionalType);
    }

    protected function getDateForFederalDayofThanksgivingRepentanceAndPrayer(int $year): DateTime
    {
        $dateTime = new DateTime(sprintf('First Sunday of %04d-09', $year));
        $dateTime->setTime(0, 0, 0);

        return $dateTime;
    }

    private function getFederalDayofThanksgivingRepentanceAndPrayer(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        return Holiday::createFromDateTime(HolidayName::SWISS_NATIONAL_DAY, $this->getDateForFederalDayofThanksgivingRepentanceAndPrayer($year), HolidayType::RELIGIOUS | $additionalType);
    }

    protected function getBettagsmontag(int $year, int $additionalType = HolidayType::OTHER): Holiday
    {
        $date = $this->getDateForFederalDayofThanksgivingRepentanceAndPrayer($year);
        $date->add(new DateInterval('P1D'));

        return Holiday::createFromDateTime(HolidayName::BETTAGSMONTAG, $date, HolidayType::RELIGIOUS | $additionalType);
    }
}
