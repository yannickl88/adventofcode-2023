<?php
declare(strict_types=1);

namespace App\Command;

use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: '_template')]
class DayTemplate extends Command
{
    protected function configure(): void
    {
        $this->addArgument("input", InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->part2($input, $output);
    }

    protected function part1(InputInterface $input, OutputInterface $output): int
    {
        $total = Stream::of(file($input->getArgument('input')));

        dump($total);

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {

        return self::SUCCESS;
    }
}
