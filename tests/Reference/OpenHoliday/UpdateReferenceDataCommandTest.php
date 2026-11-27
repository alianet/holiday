<?php

declare(strict_types=1);

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\OpenHoliday;

use JsonException;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class UpdateReferenceDataCommandTest extends HolidayTestCase
{
    private string $directory;

    /** @var list<string> */
    private array $countries;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/holiday-reference-' . bin2hex(random_bytes(8));
        mkdir($this->directory . '/Data', 0o777, true);
        copy(__DIR__ . '/sources.json', $this->directory . '/sources.json');
        $manifest = json_decode((string) file_get_contents(__DIR__ . '/sources.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($manifest);
        self::assertIsArray($manifest['files']);
        $this->countries = [];
        foreach (array_keys($manifest['files']) as $country) {
            self::assertIsString($country);
            $this->countries[] = $country;
        }
        foreach ($this->countries as $country) {
            file_put_contents($this->directory . '/Data/' . $country . '.json', 'original');
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->countries as $country) {
            unlink($this->directory . '/Data/' . $country . '.json');
        }
        unlink($this->directory . '/sources.json');
        rmdir($this->directory . '/Data');
        rmdir($this->directory);
    }

    public function testUpdatesCalendarsAndHashes(): void
    {
        $requested = [];
        $download = static function (string $url) use (&$requested): string {
            $requested[] = $url;
            $content = file_get_contents(__DIR__ . '/Data/PL.json');
            self::assertIsString($content);

            return $content;
        };
        $tester = new CommandTester(new UpdateReferenceDataCommand($this->directory, $download));
        self::assertSame(0, $tester->execute([]));
        self::assertCount(3 * count($this->countries), $requested);
        foreach (array_chunk($requested, 3) as $urls) {
            self::assertStringContainsString('validFrom=2020-01-01&validTo=2021-12-31', $urls[0]);
            self::assertStringContainsString('validFrom=2022-01-01&validTo=2023-12-31', $urls[1]);
            self::assertStringContainsString('validFrom=2024-01-01&validTo=2025-12-31', $urls[2]);
        }
        $content = file_get_contents($this->directory . '/sources.json');
        self::assertIsString($content);
        $manifest = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($manifest);
        self::assertIsArray($manifest['files']);
        self::assertIsString($manifest['retrievedAt']);
        self::assertNotSame('2026-11-18T17:49:24.720171+00:00', $manifest['retrievedAt']);
        foreach ($manifest['files'] as $country => $source) {
            self::assertIsArray($source);
            self::assertSame(hash_file('sha256', $this->directory . '/Data/' . $country . '.json'), $source['sha256']);
        }
        self::assertStringContainsString('Review the diff', $tester->getDisplay());
    }

    public function testCombinesYearRangesInRequestOrder(): void
    {
        $records = json_decode((string) file_get_contents(__DIR__ . '/Data/PL.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($records);
        $record = $records[0];
        self::assertIsArray($record);
        $download = static function (string $url) use ($record): string {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            $record['startDate'] = $query['validFrom'];
            $record['endDate'] = $query['validFrom'];

            return json_encode([$record], JSON_THROW_ON_ERROR);
        };
        self::assertSame(0, (new CommandTester(new UpdateReferenceDataCommand($this->directory, $download)))->execute([]));
        foreach ($this->countries as $country) {
            $saved = json_decode((string) file_get_contents($this->directory . '/Data/' . $country . '.json'), true, 512, JSON_THROW_ON_ERROR);
            self::assertIsArray($saved);
            self::assertSame(['2020-01-01', '2022-01-01', '2024-01-01'], array_column($saved, 'startDate'));
        }
    }

    public function testFailedDownloadLeavesAllFilesUntouched(): void
    {
        $before = file_get_contents($this->directory . '/sources.json');
        $calls = 0;
        $download = static function (string $url) use (&$calls): string {
            if (++$calls === 2) {
                throw new RuntimeException('Network failure');
            }
            $content = file_get_contents(__DIR__ . '/Data/PL.json');
            self::assertIsString($content);

            return $content;
        };

        try {
            (new CommandTester(new UpdateReferenceDataCommand($this->directory, $download)))->execute([]);
            self::fail('Expected download failure');
        } catch (RuntimeException $exception) {
            self::assertSame('Network failure', $exception->getMessage());
        }
        self::assertSame($before, file_get_contents($this->directory . '/sources.json'));
        foreach ($this->countries as $country) {
            self::assertSame('original', file_get_contents($this->directory . '/Data/' . $country . '.json'));
        }
    }

    public function testPreservesBankAndOptionalHolidays(): void
    {
        $records = json_decode((string) file_get_contents(__DIR__ . '/Data/PL.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($records);
        $records = array_slice($records, 0, 2);
        self::assertIsArray($records[0]);
        self::assertIsArray($records[1]);
        $records[0]['type'] = 'Bank';
        $records[1]['type'] = 'Optional';
        $response = json_encode($records, JSON_THROW_ON_ERROR);
        $tester = new CommandTester(new UpdateReferenceDataCommand($this->directory, static fn (string $url): string => $response));
        self::assertSame(0, $tester->execute([]));
        $saved = json_decode((string) file_get_contents($this->directory . '/Data/PL.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(array_merge($records, $records, $records), $saved);
    }

    public function testRejectsUnknownHolidayType(): void
    {
        $records = json_decode((string) file_get_contents(__DIR__ . '/Data/PL.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($records);
        self::assertIsArray($records[0]);
        $records[0]['type'] = 'Unknown';
        $response = json_encode($records, JSON_THROW_ON_ERROR);
        $this->expectException(RuntimeException::class);
        (new CommandTester(new UpdateReferenceDataCommand($this->directory, static fn (string $url): string => $response)))->execute([]);
    }

    public function testRejectsEmptyResponse(): void
    {
        $this->expectException(RuntimeException::class);
        (new CommandTester(new UpdateReferenceDataCommand($this->directory, static fn (string $url): string => '[]')))->execute([]);
    }

    public function testRejectsInvalidJson(): void
    {
        $this->expectException(JsonException::class);
        (new CommandTester(new UpdateReferenceDataCommand($this->directory, static fn (string $url): string => '<html>Error</html>')))->execute([]);
    }
}
