<?php
declare(strict_types=1);

namespace App\Command;

use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'day6')]
class Day6 extends Command
{
    protected function configure(): void
    {
        $this->addArgument("input", InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->part1($input, $output);
    }

    protected function part1(InputInterface $input, OutputInterface $output): int
    {
        $input = file($input->getArgument('input'));
        $times = array_map('intval', preg_split('/ +/', trim(substr($input[0], 9))));
        $distances = array_map('intval', preg_split('/ +/', trim(substr($input[1], 9))));
        $possible_wins = [];

        for ($i = 0; $i < count($times); $i++) {
            $wins = 0;
            for ($j = 0; $j <= $times[$i]; $j++) {
                $travel_distance = $j * ($times[$i] - $j);

                if ($travel_distance > $distances[$i]) {
                    $wins++;
                }
            }

            $possible_wins[] = $wins;
        }

        dump(array_reduce($possible_wins, fn ($carry, $current) => $carry * $current, 1));

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {

        return self::SUCCESS;
    }
}
