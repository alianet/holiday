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

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('test:reference:azure:update', 'Download and export the Azure Open Datasets holiday snapshot.')]
final class UpdateReferenceDataCommand extends Command
{
    /**
     * @param (Closure(string): string)|null $download
     * @param (Closure(string): string)|null $convert
     */
    public function __construct(private readonly string $directory = __DIR__, private readonly ?Closure $download = null, private readonly ?Closure $convert = null)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $manifest = $this->readManifest();
        $url = $manifest['url'] ?? null;
        if (!is_string($url) || !str_starts_with($url, 'https://azureopendatastorage.blob.core.windows.net/holidaydatacontainer/Processed/')) {
            throw new RuntimeException('Invalid Azure snapshot URL.');
        }
        $output->writeln('Downloading Azure holiday snapshot');
        $parquet = $this->download !== null ? ($this->download)($url) : $this->fetch($url);
        if (!str_starts_with($parquet, 'PAR1') || !str_ends_with($parquet, 'PAR1')) {
            throw new RuntimeException('Expected an Azure Parquet file.');
        }
        $response = $this->convert !== null ? ($this->convert)($parquet) : $this->export($parquet);
        $records = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($records) || !array_is_list($records) || $records === []
            || !isset($manifest['countries'], $manifest['years']) || !is_array($manifest['countries']) || $manifest['countries'] === []
            || !is_array($manifest['years']) || $manifest['years'] === []) {
            throw new RuntimeException('Invalid Azure export or coverage configuration.');
        }
        $coverage = [];
        foreach ($records as $record) {
            if (!is_array($record) || !isset($record['countryRegionCode'], $record['date'], $record['holidayName'])
                || !is_string($record['countryRegionCode']) || !is_string($record['date']) || !is_string($record['holidayName']) || $record['holidayName'] === ''
                || preg_match('/^\d{4}-\d{2}-\d{2}$/D', $record['date']) !== 1
                || !in_array($record['countryRegionCode'], $manifest['countries'], true)
                || !in_array((int) substr($record['date'], 0, 4), $manifest['years'], true)) {
                throw new RuntimeException('Invalid Azure holiday record.');
            }
            $coverage[$record['countryRegionCode'] . '-' . substr($record['date'], 0, 4)] = true;
        }
        foreach ($manifest['countries'] as $country) {
            foreach ($manifest['years'] as $year) {
                if (!is_string($country) || !is_int($year) || !isset($coverage[$country . '-' . $year])) {
                    throw new RuntimeException('Azure export does not cover all configured calendars.');
                }
            }
        }
        $content = self::encode($records);
        $manifest['parquetSha256'] = hash('sha256', $parquet);
        $manifest['sha256'] = hash('sha256', $content);
        $manifest['retrievedAt'] = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DATE_ATOM);
        $metadata = self::encode($manifest);
        $this->replaceFile($this->directory . '/Data/holidays.json', $content);
        $this->replaceFile($this->directory . '/source.json', $metadata);
        $output->writeln('<info>Azure reference data updated. Review the diff and run php vendor/bin/phpunit tests/Reference/Azure.</info>');

        return Command::SUCCESS;
    }

    /** @return array<mixed> */
    private function readManifest(): array
    {
        $content = file_get_contents($this->directory . '/source.json');
        if ($content === false) {
            throw new RuntimeException('Cannot read the reference source manifest.');
        }
        $manifest = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($manifest)) {
            throw new RuntimeException('Invalid reference source manifest.');
        }

        return $manifest;
    }

    private function fetch(string $url): string
    {
        $context = stream_context_create(['http' => ['timeout' => 30, 'follow_location' => 0, 'header' => "Accept: application/octet-stream\r\nUser-Agent: holiday-reference-updater\r\n"]]);
        $content = @file_get_contents($url, false, $context);
        if ($content === false) {
            throw new RuntimeException('Could not download ' . $url);
        }

        return $content;
    }

    private function export(string $parquet): string
    {
        $temporary = tempnam(sys_get_temp_dir(), 'holiday-parquet-');
        if ($temporary === false) {
            throw new RuntimeException('Cannot create temporary Parquet file.');
        }

        try {
            if (file_put_contents($temporary, $parquet) !== strlen($parquet)) {
                throw new RuntimeException('Cannot write temporary Parquet file.');
            }
            $python = getenv('AZURE_REFERENCE_PYTHON') ?: 'python3';
            $process = proc_open([$python, __DIR__ . '/export.py', $this->directory . '/source.json', $temporary], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
            if (!is_resource($process)) {
                throw new RuntimeException('Cannot start Azure Parquet converter.');
            }
            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            if (proc_close($process) !== 0 || $stdout === false) {
                throw new RuntimeException('Azure conversion failed. Install Python dependencies from tests/Reference/Azure/requirements.txt or use the reference-updater Docker target. ' . $stderr);
            }

            return $stdout;
        } finally {
            unlink($temporary);
        }
    }

    /** @param array<mixed> $data */
    private static function encode(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
    }

    private function replaceFile(string $path, string $content): void
    {
        $temporary = tempnam(dirname($path), '.reference-');
        if ($temporary === false) {
            throw new RuntimeException('Cannot create a temporary reference file.');
        }

        try {
            if (file_put_contents($temporary, $content) !== strlen($content) || !rename($temporary, $path)) {
                throw new RuntimeException('Cannot update ' . $path);
            }
        } finally {
            if (is_file($temporary)) {
                unlink($temporary);
            }
        }
    }
}
