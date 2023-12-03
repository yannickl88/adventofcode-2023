<?php
declare(strict_types=1);

namespace App\Command;

use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'day2')]
class Day2 extends Command
{
    private array $max = ['r' => 12, 'g' => 13, 'b' => 14];

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
        $total = Stream::of(file($input->getArgument('input')))
            ->map(fn ($line) => $this->parseGame(trim($line)))
            ->filter(function (array $game) {
                foreach ($game[1] as $set) {
                    if ($set['r'] > $this->max['r'] || $set['g'] > $this->max['g'] || $set['b'] > $this->max['b']) {
                        return false;
                    }
                }

                return true;
            })
            ->reduce(fn ($carry, $current) => $carry + $current[0], 0);

        dump($total);

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {
        $total = Stream::of(file($input->getArgument('input')))
            ->map(fn ($line) => $this->parseGame(trim($line)))
            ->map(fn ($game) => $this->findMax($game[0], Stream::of($game[1])))
            ->map(fn ($game) => $game[1]['r'] * $game[1]['g'] * $game[1]['b'])
            ->reduce(fn ($carry, $power) => $carry + $power);

        dump($total);

        return self::SUCCESS;
    }

    private function parseGame(string $line): array
    {
        preg_match('/^Game (\d+): (.+)$/', $line, $matches);

        return [(int) $matches[1], array_map(function (string $set) {
            $result = ['r' => 0, 'g' => 0, 'b' => 0];

            foreach (explode(', ', $set) as $dice) {
                [$number, $type] = explode(' ', $dice);

                if ($type == 'red') {
                    $result['r'] += (int) $number;
                } elseif ($type == 'green') {
                    $result['g'] += (int) $number;
                } elseif ($type == 'blue') {
                    $result['b'] += (int) $number;
                }
            }

            return $result;
        }, explode('; ', $matches[2]))];
    }

    private function findMax(int $game, Stream $sets): array
    {
        return [$game, $sets->reduce(fn ($carry, $set) => [
            'r' => max($carry['r'], $set['r']),
            'g' => max($carry['g'], $set['g']),
            'b' => max($carry['b'], $set['b'])
        ], ['r' => 0, 'g' => 0, 'b' => 0])];
    }
}
