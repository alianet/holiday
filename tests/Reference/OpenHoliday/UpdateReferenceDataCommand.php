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

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('test:reference:openholidays:update', 'Download OpenHolidays reference calendars and update their provenance.')]
final class UpdateReferenceDataCommand extends Command
{
    /** @param (Closure(string): string)|null $download */
    public function __construct(private readonly string $directory = __DIR__, private readonly ?Closure $download = null)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $manifest = $this->readManifest();
        if (!isset($manifest['files']) || !is_array($manifest['files']) || $manifest['files'] === []) {
            throw new RuntimeException('The source manifest must contain reference files.');
        }
        $pending = [];
        foreach ($manifest['files'] as $country => $source) {
            if (!is_string($country) || preg_match('/^[A-Z]{2}$/D', $country) !== 1 || !is_array($source) || !isset($source['url']) || !is_string($source['url'])) {
                throw new RuntimeException('Invalid source manifest entry.');
            }
            $url = $source['url'];
            if (!str_starts_with($url, 'https://openholidaysapi.org/PublicHolidays?')) {
                throw new RuntimeException('Unexpected reference source URL: ' . $url);
            }
            $output->writeln('Downloading ' . $country);
            $response = $this->download !== null ? ($this->download)($url) : $this->fetch($url);
            $records = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($records) || !array_is_list($records) || $records === []) {
                throw new RuntimeException('Expected a non-empty holiday list for ' . $country);
            }
            foreach ($records as $record) {
                if (!is_array($record) || !isset($record['startDate'], $record['endDate'], $record['name'], $record['type'], $record['nationwide'])
                    || !is_string($record['startDate']) || !is_string($record['endDate'])
                    || preg_match('/^\d{4}-\d{2}-\d{2}$/D', $record['startDate']) !== 1
                    || preg_match('/^\d{4}-\d{2}-\d{2}$/D', $record['endDate']) !== 1
                    || !is_array($record['name']) || $record['name'] === [] || $record['type'] !== 'Public' || !is_bool($record['nationwide'])) {
                    throw new RuntimeException('Invalid holiday record for ' . $country);
                }
            }
            $content = self::encode($records);
            $pending[$this->directory . '/Data/' . $country . '.json'] = $content;
            $source['sha256'] = hash('sha256', $content);
            $manifest['files'][$country] = $source;
        }
        $manifest['retrievedAt'] = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DATE_ATOM);
        $pending[$this->directory . '/sources.json'] = self::encode($manifest);

        // Complete all downloads and validation before replacing any local fixture.
        foreach ($pending as $path => $content) {
            $this->replaceFile($path, $content);
        }
        $output->writeln('<info>Reference data updated. Review the diff and run php vendor/bin/phpunit tests/Reference/OpenHoliday.</info>');

        return Command::SUCCESS;
    }

    /** @return array<mixed> */
    private function readManifest(): array
    {
        $content = file_get_contents($this->directory . '/sources.json');
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
        $context = stream_context_create(['http' => ['timeout' => 30, 'follow_location' => 0, 'header' => "Accept: application/json\r\nUser-Agent: holiday-reference-updater\r\n"]]);
        $content = @file_get_contents($url, false, $context);
        if ($content === false) {
            throw new RuntimeException('Could not download ' . $url);
        }

        return $content;
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
