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

namespace Umulmrum\Holiday\Provider\Spain;

use Umulmrum\Holiday\Constant\HolidayName;

final class Formentera extends Island
{
    protected const REGION = 'IB';

    protected const HOLIDAYS = [
        2008 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS]],
        2009 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2010 => [['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2011 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['05-30', HolidayName::SAINT_FERDINAND_DAY]],
        2012 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2013 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS]],
        2014 => [['05-30', HolidayName::SAINT_FERDINAND_DAY], ['07-25', HolidayName::SAINT_JAMES_DAY]],
        2015 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2016 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS]],
        2017 => [['05-30', HolidayName::SAINT_FERDINAND_DAY], ['07-25', HolidayName::SAINT_JAMES_DAY]],
        2018 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2019 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS]],
        2020 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2021 => [['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2022 => [['05-30', HolidayName::SAINT_FERDINAND_DAY], ['07-25', HolidayName::SAINT_JAMES_DAY]],
        2023 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-26', HolidayName::SECOND_CHRISTMAS_DAY]],
        2024 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2025 => [['05-30', HolidayName::SAINT_FERDINAND_DAY], ['07-25', HolidayName::SAINT_JAMES_DAY]],
        2026 => [['07-25', HolidayName::SAINT_JAMES_DAY], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
        2027 => [['08-05', HolidayName::OUR_LADY_OF_THE_SNOWS], ['12-03', HolidayName::SAINT_FRANCIS_XAVIER_DAY]],
    ];
}
