<?php

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

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/holiday-reference-' . bin2hex(random_bytes(8));
        mkdir($this->directory . '/Data', 0o777, true);
        copy(__DIR__ . '/sources.json', $this->directory . '/sources.json');
        foreach (['PL', 'DE', 'CZ', 'AT'] as $country) {
            file_put_contents($this->directory . '/Data/' . $country . '.json', 'original');
        }
    }

    protected function tearDown(): void
    {
        foreach (['PL', 'DE', 'CZ', 'AT'] as $country) {
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
        self::assertCount(4, $requested);
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
        foreach (['PL', 'DE', 'CZ', 'AT'] as $country) {
            self::assertSame('original', file_get_contents($this->directory . '/Data/' . $country . '.json'));
        }
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
