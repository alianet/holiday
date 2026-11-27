<?php

declare(strict_types=1);

namespace Umulmrum\Holiday\Provider\Japan;

use DateTimeImmutable;
use InvalidArgumentException;
use Umulmrum\Holiday\Assert\Year;
use Umulmrum\Holiday\Constant\HolidayName;
use Umulmrum\Holiday\Constant\HolidayType;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Provider\HolidayProviderInterface;

use function array_values;
use function floor;
use function ksort;
use function sprintf;

/**
 * National holidays under the Public Holidays Act.
 * Source: https://www8.cao.go.jp/chosei/shukujitsu/gaiyou.html
 * Equinox dates after the officially announced year are predictions (1980–2099 formula).
 */
class Japan implements HolidayProviderInterface
{
    public function calculateHolidaysForYear(int $year): HolidayList
    {
        Year::validate($year);
        if ($year < 1980 || $year > 2099) {
            throw new InvalidArgumentException('Japan supports years between 1980 and 2099, got ' . $year);
        }

        /** @var array<string, string> $dates */
        $dates = [];
        $add = static function (string $date, string $name) use (&$dates, $year): void {
            $dates[(new DateTimeImmutable(sprintf('%04d-', $year) . $date))->format('Y-m-d')] = $name;
        };
        $add('01-01', HolidayName::NEW_YEAR);
        $add($year >= 2000 ? '01-08 this monday' : '01-15', HolidayName::COMING_OF_AGE_DAY);
        $add('02-11', HolidayName::JAPAN_FOUNDATION_DAY);
        if ($year >= 2020) {
            $add('02-23', HolidayName::EMPERORS_BIRTHDAY);
        } elseif ($year >= 1989 && $year <= 2018) {
            $add('12-23', HolidayName::EMPERORS_BIRTHDAY);
        }
        $offset = 0.242194 * ($year - 1980) - floor(($year - 1980) / 4);
        $add(sprintf('03-%02d', (int) floor(20.8431 + $offset)), HolidayName::VERNAL_EQUINOX_DAY);
        $add('04-29', $year < 1989 ? HolidayName::EMPERORS_BIRTHDAY : ($year < 2007 ? HolidayName::GREENERY_DAY : HolidayName::SHOWA_DAY));
        $add('05-03', HolidayName::JAPAN_CONSTITUTION_DAY);
        if ($year >= 2007) {
            $add('05-04', HolidayName::GREENERY_DAY);
        }
        $add('05-05', HolidayName::JAPAN_CHILDRENS_DAY);
        if ($year >= 1996) {
            $add(match ($year) {
                2020 => '07-23',
                2021 => '07-22',
                default => $year >= 2003 ? '07-15 this monday' : '07-20',
            }, HolidayName::MARINE_DAY);
        }
        if ($year >= 2016) {
            $add(match ($year) {
                2020 => '08-10',
                2021 => '08-08',
                default => '08-11',
            }, HolidayName::MOUNTAIN_DAY);
        }
        $add($year >= 2003 ? '09-15 this monday' : '09-15', HolidayName::RESPECT_FOR_THE_AGED_DAY);
        $add(sprintf('09-%02d', (int) floor(23.2488 + $offset)), HolidayName::AUTUMNAL_EQUINOX_DAY);
        $add(match ($year) {
            2020 => '07-24',
            2021 => '07-23',
            default => $year >= 2000 ? '10-08 this monday' : '10-10',
        }, HolidayName::SPORTS_DAY);
        $add('11-03', HolidayName::CULTURE_DAY);
        $add('11-23', HolidayName::LABOR_THANKSGIVING_DAY);
        foreach (match ($year) {
            1989 => ['02-24' => HolidayName::STATE_FUNERAL_OF_EMPEROR_SHOWA],
            1990 => ['11-12' => HolidayName::ENTHRONEMENT_CEREMONY_DAY],
            1993 => ['06-09' => HolidayName::ROYAL_WEDDING],
            2019 => ['05-01' => HolidayName::ENTHRONEMENT_DAY, '10-22' => HolidayName::ENTHRONEMENT_CEREMONY_DAY],
            default => [],
        } as $date => $name) {
            $add($date, $name);
        }

        $holidays = [];
        foreach ($dates as $date => $name) {
            $holidays[$date] = Holiday::create($name, $date, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
        }
        // Only national holidays trigger substitutes or bridge days; derived days do not.
        foreach ($dates as $date => $name) {
            $day = new DateTimeImmutable($date);
            if ($day->format('N') === '7') {
                $substitute = $day->modify('+1 day');
                if ($year >= 2007) {
                    while (isset($dates[$substitute->format('Y-m-d')])) {
                        $substitute = $substitute->modify('+1 day');
                    }
                }
                $key = $substitute->format('Y-m-d');
                if (!isset($holidays[$key])) {
                    $holidays[$key] = Holiday::create($name . HolidayName::SUFFIX_COMPENSATORY, $key, HolidayType::OFFICIAL | HolidayType::DAY_OFF | HolidayType::COMPENSATORY);
                }
            }
            if ($year >= 1986) {
                $bridge = $day->modify('+1 day');
                $key = $bridge->format('Y-m-d');
                if ($bridge->format('N') !== '7' && !isset($holidays[$key]) && isset($dates[$day->modify('+2 days')->format('Y-m-d')])) {
                    $holidays[$key] = Holiday::create(HolidayName::CITIZENS_HOLIDAY, $key, HolidayType::OFFICIAL | HolidayType::DAY_OFF);
                }
            }
        }
        ksort($holidays);

        return new HolidayList(array_values($holidays));
    }
}
