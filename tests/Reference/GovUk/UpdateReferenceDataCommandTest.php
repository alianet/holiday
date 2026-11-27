<?php

declare(strict_types=1);

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 * (c) Mariusz Kaczanowski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\GovUk;

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
        $this->directory = sys_get_temp_dir() . '/holiday-govuk-' . bin2hex(random_bytes(8));
        mkdir($this->directory . '/Data', 0o777, true);
        copy(__DIR__ . '/source.json', $this->directory . '/source.json');
        file_put_contents($this->directory . '/Data/bank-holidays.json', 'original');
    }

    protected function tearDown(): void
    {
        unlink($this->directory . '/Data/bank-holidays.json');
        unlink($this->directory . '/source.json');
        rmdir($this->directory . '/Data');
        rmdir($this->directory);
    }

    public function testUpdatesDataAndProvenance(): void
    {
        $response = file_get_contents(__DIR__ . '/Data/bank-holidays.json');
        self::assertIsString($response);
        $download = static function (string $url) use ($response): string {
            self::assertSame('https://www.gov.uk/bank-holidays.json', $url);

            return $response;
        };
        $tester = new CommandTester(new UpdateReferenceDataCommand($this->directory, $download));
        self::assertSame(0, $tester->execute([]));
        $content = file_get_contents($this->directory . '/source.json');
        self::assertIsString($content);
        $metadata = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($metadata);
        self::assertSame(hash_file('sha256', $this->directory . '/Data/bank-holidays.json'), $metadata['sha256']);
        self::assertSame('Open Government Licence v3.0', $metadata['license']);
        self::assertSame(range(2019, 2028), $metadata['years']);
        self::assertIsString($metadata['retrievedAt']);
        self::assertStringContainsString('Review the diff', $tester->getDisplay());
    }

    #[DataProvider('provideInvalidResponses')]
    public function testInvalidResponseLeavesFilesUntouched(string $response): void
    {
        $before = file_get_contents($this->directory . '/source.json');

        try {
            (new CommandTester(new UpdateReferenceDataCommand($this->directory, static fn (string $url): string => $response)))->execute([]);
            self::fail('Expected an invalid reference response to fail');
        } catch (JsonException|RuntimeException) {
            self::assertSame($before, file_get_contents($this->directory . '/source.json'));
            self::assertSame('original', file_get_contents($this->directory . '/Data/bank-holidays.json'));
        }
    }

    /** @return Generator<string, array{string}> */
    public static function provideInvalidResponses(): Generator
    {
        yield 'invalid JSON' => ['<html>Error</html>'];

        yield 'missing regions' => ['{}'];
        $content = file_get_contents(__DIR__ . '/Data/bank-holidays.json');
        self::assertIsString($content);
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($data);
        $data['scotland'] = ['division' => 'scotland', 'events' => []];

        yield 'empty events' => [json_encode($data, JSON_THROW_ON_ERROR)];
        $data['scotland']['events'] = [['title' => 'New Year', 'date' => '2025-01-01', 'notes' => '']];

        yield 'incomplete year coverage' => [json_encode($data, JSON_THROW_ON_ERROR)];
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
        self::assertSame('original', file_get_contents($this->directory . '/Data/bank-holidays.json'));
    }
}
