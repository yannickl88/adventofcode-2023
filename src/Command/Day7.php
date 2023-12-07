<?php
declare(strict_types=1);

namespace App\Command;

use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'day7')]
class Day7 extends Command
{
    private const STRENGTH = [
        'J',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'T',
        'J',
        'Q',
        'K',
        'A',
    ];

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
        $hands = Stream::of(file($input->getArgument('input')))
            ->map(fn(string $line) => explode(" ", trim($line)))
            ->map(fn(array $hand) => [
                $this->determineType(count_chars($hand[0], 1)),
                intval($hand[1]),
                $hand[0],
            ])
            ->sort(fn (array $a, array $b) => $a[0] === $b[0] ? $this->compareStrength($a[2], $b[2]) : $a[0] <=> $b[0])
            ->toArray();

        $total = [];

        foreach ($hands as $i => [$type, $score, $hand]) {
            $total[] = $score * ($i + 1);
        }

        dump(array_sum($total));

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {

        return self::SUCCESS;
    }

    private function determineType(array $hand): int
    {
        if (count($hand) === 1) {
            return 7;
        }
        if (in_array(4, $hand)) {
            return 6;
        }
        if (in_array(3, $hand) && in_array(2, $hand)) {
            return 5;
        }
        if (in_array(3, $hand)) {
            return 4;
        }
        if (in_array(2, $hand)) {
            if (array_count_values($hand)[2] === 2) {
                return 3;
            }
            return 2;
        }
        if (count($hand) === 5) {
            return 1;
        }

        return 0;
    }

    private function compareStrength(string $a, string $b): int
    {
        for ($i = 0; $i < 5; $i++) {
            if ($a[$i] === $b[$i]) {
                continue;
            }

            return array_search($a[$i], self::STRENGTH) <=> array_search($b[$i], self::STRENGTH);
        }

        return 0;
    }
}
