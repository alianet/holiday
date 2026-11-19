<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference\GovUk;

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('test:reference:govuk:update', 'Download official GOV.UK bank holidays and update provenance.')]
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
        $url = 'https://www.gov.uk/bank-holidays.json';
        $output->writeln('Downloading ' . $url);
        $response = $this->download !== null ? ($this->download)($url) : $this->fetch($url);
        $data = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data) || !isset($manifest['years']) || !is_array($manifest['years']) || $manifest['years'] === []) {
            throw new RuntimeException('Invalid GOV.UK response or reference years.');
        }
        foreach (['england-and-wales', 'scotland', 'northern-ireland'] as $division) {
            $region = $data[$division] ?? null;
            if (!is_array($region) || ($region['division'] ?? null) !== $division || !isset($region['events']) || !is_array($region['events']) || !array_is_list($region['events']) || $region['events'] === []) {
                throw new RuntimeException('Missing GOV.UK events for ' . $division);
            }
            $years = [];
            foreach ($region['events'] as $event) {
                if (!is_array($event) || !isset($event['date'], $event['title'], $event['notes'])
                    || !is_string($event['date']) || preg_match('/^\d{4}-\d{2}-\d{2}$/D', $event['date']) !== 1
                    || !is_string($event['title']) || $event['title'] === '' || !is_string($event['notes'])) {
                    throw new RuntimeException('Invalid GOV.UK event for ' . $division);
                }
                $years[] = (int) substr($event['date'], 0, 4);
            }
            foreach ($manifest['years'] as $year) {
                if (!is_int($year) || !in_array($year, $years, true)) {
                    throw new RuntimeException('Response does not cover all configured years for ' . $division);
                }
            }
        }
        $content = self::encode($data);
        $manifest['url'] = $url;
        $manifest['sha256'] = hash('sha256', $content);
        $manifest['retrievedAt'] = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format(DATE_ATOM);
        $metadata = self::encode($manifest);
        $this->replaceFile($this->directory . '/Data/bank-holidays.json', $content);
        $this->replaceFile($this->directory . '/source.json', $metadata);
        $output->writeln('<info>GOV.UK reference data updated. Review the diff and run php vendor/bin/phpunit tests/Reference/GovUk.</info>');

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
