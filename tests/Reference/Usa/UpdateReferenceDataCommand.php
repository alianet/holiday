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

namespace Umulmrum\Holiday\Test\Reference\Usa;

use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('test:reference:usa:update', 'Export pinned python-holidays calendars for all US states and DC.')]
final class UpdateReferenceDataCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $python = getenv('USA_REFERENCE_PYTHON') ?: 'python3';
        $process = proc_open([$python, __DIR__ . '/update.py', __DIR__], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            throw new RuntimeException('Cannot start the US reference exporter.');
        }
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        if (proc_close($process) !== 0) {
            throw new RuntimeException('US reference export failed. Install tests/Reference/Usa/requirements.txt or use the reference-updater Docker target. ' . $stderr);
        }
        $output->write($stdout === false ? '' : $stdout);
        $output->writeln('<info>US state reference data updated. Review the diff and run php vendor/bin/phpunit tests/Reference/Usa.</info>');

        return Command::SUCCESS;
    }
}
