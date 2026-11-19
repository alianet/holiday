<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Umulmrum\Holiday\Test\Reference;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umulmrum\Holiday\Test\Reference\Azure\UpdateReferenceDataCommand as UpdateAzureCommand;
use Umulmrum\Holiday\Test\Reference\GovUk\UpdateReferenceDataCommand as UpdateGovUkCommand;
use Umulmrum\Holiday\Test\Reference\OpenHoliday\UpdateReferenceDataCommand as UpdateOpenHolidaysCommand;

#[AsCommand('test:reference:update', 'Update all reference calendars: OpenHolidays, GOV.UK and Azure.')]
final class UpdateReferenceDataCommand extends Command
{
    public function __construct(private readonly Command $openHolidays = new UpdateOpenHolidaysCommand(), private readonly Command $govUk = new UpdateGovUkCommand(), private readonly Command $azure = new UpdateAzureCommand())
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ([$this->openHolidays, $this->govUk, $this->azure] as $command) {
            $status = $command->run(new ArrayInput([]), $output);
            if ($status !== Command::SUCCESS) {
                return $status;
            }
        }
        $output->writeln('<info>All reference data updated. Run php vendor/bin/phpunit tests/Reference.</info>');

        return Command::SUCCESS;
    }
}
