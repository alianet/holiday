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

namespace Umulmrum\Holiday\Formatter;

use Psr\Clock\ClockInterface;
use Umulmrum\Holiday\Helper\SystemClock;
use Umulmrum\Holiday\Model\Holiday;
use Umulmrum\Holiday\Model\HolidayList;
use Umulmrum\Holiday\Translator\NullTranslator;
use Umulmrum\Holiday\Translator\TranslatorInterface;

use function implode;

final readonly class ICalendarFormatter implements HolidayFormatterInterface
{
    public const LINE_ENDING = "\r\n";

    public function __construct(
        private TranslatorInterface $translator = new NullTranslator(),
        private ClockInterface $clock = new SystemClock(),
        private ?string $locale = null,
    ) {}

    public function format(Holiday $holiday): string
    {
        return $this->getEvent($holiday);
    }

    public function formatList(HolidayList $holidayList): string
    {
        $result = [];

        $result[] = $this->getHeader();
        foreach ($holidayList->getList() as $holiday) {
            $result[] = $this->getEvent($holiday);
        }
        $result[] = $this->getFooter();

        return implode(self::LINE_ENDING, $result) . self::LINE_ENDING;
    }

    public function getHeader(): string
    {
        return implode(self::LINE_ENDING, [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:alianet/holiday',
            'CALSCALE:GREGORIAN',
        ]);
    }

    private function getEvent(Holiday $holiday): string
    {
        $dtstamp = $this->clock->now();

        return implode(self::LINE_ENDING, [
            'BEGIN:VEVENT',
            sprintf('UID:%s-%s', $holiday->getName(), $holiday->getSimpleDate()),
            sprintf('DTSTAMP:%s', $dtstamp->format('Ymd\THis\ZO')),
            sprintf('CREATED:%s', $dtstamp->format('Ymd\THis\ZO')),
            'SUMMARY:' . $this->translator->translateName($holiday, $this->locale),
            'DTSTART;VALUE=DATE:' . $holiday->getDate()->format('Ymd'),
            'END:VEVENT',
        ]);
    }

    public function getFooter(): string
    {
        return 'END:VCALENDAR';
    }
}
