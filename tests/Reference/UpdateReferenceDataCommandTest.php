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

namespace Umulmrum\Holiday\Test\Reference;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Umulmrum\Holiday\Test\HolidayTestCase;

final class UpdateReferenceDataCommandTest extends HolidayTestCase
{
    public function testUpdatesAllSourcesInOrder(): void
    {
        $openHolidays = $this->createMock(Command::class);
        $openHolidays->expects(self::once())->method('run')->willReturnCallback(static function (InputInterface $input, OutputInterface $output): int {
            $output->writeln('OpenHolidays updated');

            return Command::SUCCESS;
        });
        $govUk = $this->createMock(Command::class);
        $govUk->expects(self::once())->method('run')->willReturnCallback(static function (InputInterface $input, OutputInterface $output): int {
            $output->writeln('GOV.UK updated');

            return Command::SUCCESS;
        });
        $azure = $this->createMock(Command::class);
        $azure->expects(self::once())->method('run')->willReturnCallback(static function (InputInterface $input, OutputInterface $output): int {
            $output->writeln('Azure updated');

            return Command::SUCCESS;
        });
        $usa = $this->createMock(Command::class);
        $usa->expects(self::once())->method('run')->willReturnCallback(static function (InputInterface $input, OutputInterface $output): int {
            $output->writeln('US states updated');

            return Command::SUCCESS;
        });
        $tester = new CommandTester(new UpdateReferenceDataCommand($openHolidays, $govUk, $azure, $usa));
        self::assertSame(Command::SUCCESS, $tester->execute([]));
        self::assertStringContainsString("OpenHolidays updated\nGOV.UK updated\nAzure updated\nUS states updated\nAll reference data updated.", $tester->getDisplay());
    }

    public function testStopsWhenFirstSourceFails(): void
    {
        $openHolidays = $this->createMock(Command::class);
        $openHolidays->expects(self::once())->method('run')->willReturn(Command::FAILURE);
        $govUk = $this->createMock(Command::class);
        $govUk->expects(self::never())->method('run');
        $azure = $this->createMock(Command::class);
        $azure->expects(self::never())->method('run');
        $tester = new CommandTester(new UpdateReferenceDataCommand($openHolidays, $govUk, $azure));
        self::assertSame(Command::FAILURE, $tester->execute([]));
        self::assertStringNotContainsString('All reference data updated', $tester->getDisplay());
    }

    public function testPropagatesGovUkFailure(): void
    {
        $openHolidays = $this->createMock(Command::class);
        $openHolidays->expects(self::once())->method('run')->willReturn(Command::SUCCESS);
        $govUk = $this->createMock(Command::class);
        $govUk->expects(self::once())->method('run')->willReturn(Command::FAILURE);
        $azure = $this->createMock(Command::class);
        $azure->expects(self::never())->method('run');
        $tester = new CommandTester(new UpdateReferenceDataCommand($openHolidays, $govUk, $azure));
        self::assertSame(Command::FAILURE, $tester->execute([]));
        self::assertStringNotContainsString('All reference data updated', $tester->getDisplay());
    }

    public function testPropagatesDownloadException(): void
    {
        $openHolidays = $this->createMock(Command::class);
        $openHolidays->expects(self::once())->method('run')->willReturn(Command::SUCCESS);
        $govUk = $this->createMock(Command::class);
        $govUk->expects(self::once())->method('run')->willThrowException(new RuntimeException('Network failure'));
        $azure = $this->createMock(Command::class);
        $azure->expects(self::never())->method('run');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Network failure');
        (new CommandTester(new UpdateReferenceDataCommand($openHolidays, $govUk, $azure)))->execute([]);
    }

    public function testPropagatesAzureFailure(): void
    {
        $openHolidays = $this->createMock(Command::class);
        $openHolidays->expects(self::once())->method('run')->willReturn(Command::SUCCESS);
        $govUk = $this->createMock(Command::class);
        $govUk->expects(self::once())->method('run')->willReturn(Command::SUCCESS);
        $azure = $this->createMock(Command::class);
        $azure->expects(self::once())->method('run')->willReturn(Command::FAILURE);
        $tester = new CommandTester(new UpdateReferenceDataCommand($openHolidays, $govUk, $azure));
        self::assertSame(Command::FAILURE, $tester->execute([]));
        self::assertStringNotContainsString('All reference data updated', $tester->getDisplay());
    }

    public function testPropagatesUsaFailure(): void
    {
        $commands = [];
        for ($index = 0; $index < 4; ++$index) {
            $command = $this->createMock(Command::class);
            $command->expects(self::once())->method('run')->willReturn($index === 3 ? Command::FAILURE : Command::SUCCESS);
            $commands[] = $command;
        }
        $tester = new CommandTester(new UpdateReferenceDataCommand(...$commands));
        self::assertSame(Command::FAILURE, $tester->execute([]));
        self::assertStringNotContainsString('All reference data updated', $tester->getDisplay());
    }
}
