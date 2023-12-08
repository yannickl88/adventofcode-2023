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
    private const STRENGTH_JOKER = [
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
        return $this->part2($input, $output);
    }

    protected function part1(InputInterface $input, OutputInterface $output): int
    {
        $hands = Stream::of(file($input->getArgument('input')))
            ->map(fn(string $line) => explode(" ", trim($line)))
            ->map(fn(array $hand) => [
                $this->determineType($hand[0]),
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
        $hands = Stream::of(file($input->getArgument('input')))
            ->map(fn(string $line) => explode(" ", trim($line)))
            ->map(fn(array $hand) => [
                $this->determineTypeWithJoker($hand[0]),
                intval($hand[1]),
                $hand[0],
            ])
            ->sort(fn (array $a, array $b) => $a[0] === $b[0] ? $this->compareStrengthWithJoker($a[2], $b[2]) : $a[0] <=> $b[0])
            ->toArray();

        $total = [];

        foreach ($hands as $i => [$type, $score, $hand]) {
            $total[] = $score * ($i + 1);
        }

        dump(array_sum($total));

        return self::SUCCESS;
    }

    private function determineType(string $hand): int
    {
        $char_count = count_chars($hand, 1);

        if (count($char_count) === 1) {
            return 7;
        }
        if (in_array(4, $char_count)) {
            return 6;
        }
        if (in_array(3, $char_count) && in_array(2, $char_count)) {
            return 5;
        }
        if (in_array(3, $char_count)) {
            return 4;
        }
        if (in_array(2, $char_count)) {
            if (array_count_values($char_count)[2] === 2) {
                return 3;
            }
            return 2;
        }
        if (count($char_count) === strlen($hand)) {
            return 1;
        }

        return 0; // Never happens
    }

    private function determineTypeWithJoker(string $hand): int
    {
        $original_score = $this->determineType(str_replace('J', '', $hand));
        $jokers = count_chars($hand, 1)[74] ?? 0;

        if ($jokers === 5 || $jokers === 4) { // only jokers or 4J + 1* -> 5 of a kind
            return 7;
        }
        if ($jokers === 3) {
            if ($original_score === 2) { // 3J + 2 of a kind -> 5 of a kind
                return 7;
            }
            return 6; // we can always make 4 of kind
        }
        if ($jokers === 2) {
            if ($original_score === 4) { // 2J + 3 of a kind -> 5 of a kind
                return 7;
            }
            if ($original_score === 2) { // 2J + 2 of a kind -> 4 of a kind
                return 6;
            }
            if ($original_score === 1) { // 2J + highcard -> 3 of a kind
                return 4;
            }
        }
        if ($jokers === 1) {
            if ($original_score === 6) { // 1J + 4 of a kind -> 5 of a kind
                return 7;
            }
            if ($original_score === 4) { // 1J + 3 of a kind -> 4 of a kind
                return 6;
            }
            if ($original_score === 3) { // 1J + 2 pairs -> full house
                return 5;
            }
            if ($original_score === 2) { // 1J + 2 of a kind -> 3 of a kind
                return 4;
            }
            if ($original_score === 1) { // 1J + highcard -> 2 of a kind
                return 2;
            }
        }

        return $original_score;
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

    private function compareStrengthWithJoker(string $a, string $b): int
    {
        for ($i = 0; $i < 5; $i++) {
            if ($a[$i] === $b[$i]) {
                continue;
            }

            return array_search($a[$i], self::STRENGTH_JOKER) <=> array_search($b[$i], self::STRENGTH_JOKER);
        }

        return 0;
    }
}
