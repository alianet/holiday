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

namespace Umulmrum\Holiday\Test\Reference\Azure;

use Generator;
use JsonException;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class UpdateReferenceDataCommandTest extends HolidayTestCase
{
    private string $directory;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/holiday-azure-' . bin2hex(random_bytes(8));
        mkdir($this->directory . '/Data', 0o777, true);
        copy(__DIR__ . '/source.json', $this->directory . '/source.json');
        file_put_contents($this->directory . '/Data/holidays.json', 'original');
    }

    protected function tearDown(): void
    {
        unlink($this->directory . '/Data/holidays.json');
        unlink($this->directory . '/source.json');
        rmdir($this->directory . '/Data');
        rmdir($this->directory);
    }

    public function testUpdatesDataAndProvenance(): void
    {
        $configuration = file_get_contents($this->directory . '/source.json');
        self::assertIsString($configuration);
        $configuration = json_decode($configuration, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($configuration);
        $response = file_get_contents(__DIR__ . '/Data/holidays.json');
        self::assertIsString($response);
        $download = static function (string $url): string {
            self::assertStringStartsWith('https://azureopendatastorage.blob.core.windows.net/', $url);

            return 'PAR1binaryPAR1';
        };
        $tester = new CommandTester(new UpdateReferenceDataCommand($this->directory, $download, static fn (string $parquet): string => $response));
        self::assertSame(0, $tester->execute([]));
        $content = file_get_contents($this->directory . '/source.json');
        self::assertIsString($content);
        $metadata = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($metadata);
        self::assertSame(hash_file('sha256', $this->directory . '/Data/holidays.json'), $metadata['sha256']);
        self::assertSame('CC-BY-SA-3.0', $metadata['license']);
        self::assertSame(hash('sha256', 'PAR1binaryPAR1'), $metadata['parquetSha256']);
        self::assertSame($configuration['years'], $metadata['years']);
        self::assertSame($configuration['countries'], $metadata['countries']);
        self::assertIsString($metadata['retrievedAt']);
        self::assertStringContainsString('Review the diff', $tester->getDisplay());
    }

    #[DataProvider('provideInvalidResponses')]
    public function testInvalidResponseLeavesFilesUntouched(string $response): void
    {
        $before = file_get_contents($this->directory . '/source.json');

        try {
            (new CommandTester(new UpdateReferenceDataCommand($this->directory, static fn (string $url): string => 'PAR1binaryPAR1', static fn (string $parquet): string => $response)))->execute([]);
            self::fail('Expected an invalid reference response to fail');
        } catch (JsonException|RuntimeException) {
            self::assertSame($before, file_get_contents($this->directory . '/source.json'));
            self::assertSame('original', file_get_contents($this->directory . '/Data/holidays.json'));
        }
    }

    /** @return Generator<string, array{string}> */
    public static function provideInvalidResponses(): Generator
    {
        yield 'invalid JSON' => ['<html>Error</html>'];

        yield 'empty export' => ['[]'];

        yield 'invalid record' => ['[{"date":"broken"}]'];

        yield 'missing coverage' => ['[{"date":"2019-01-01","holidayName":"Neujahr","countryRegionCode":"DE"}]'];
    }

    public function testNetworkFailureLeavesFilesUntouched(): void
    {
        $before = file_get_contents($this->directory . '/source.json');
        $download = static function (string $url): string {
            throw new RuntimeException('Network failure');
        };

        try {
            (new CommandTester(new UpdateReferenceDataCommand($this->directory, $download)))->execute([]);
            self::fail('Expected network failure');
        } catch (RuntimeException $exception) {
            self::assertSame('Network failure', $exception->getMessage());
        }
        self::assertSame($before, file_get_contents($this->directory . '/source.json'));
        self::assertSame('original', file_get_contents($this->directory . '/Data/holidays.json'));
    }
}
