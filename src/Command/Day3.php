<?php
declare(strict_types=1);

namespace App\Command;

use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'day3')]
class Day3 extends Command
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
        $grid = Stream::of(file($input->getArgument('input')))->map('trim')->toArray();
        $symbols = $this->extractSymbols($grid);
        $numbers = [];

        foreach ($grid as $y => $line) {
            if (false === preg_match_all('/\d+/', $line, $matches, PREG_OFFSET_CAPTURE)) {
                continue;
            }

            foreach ($matches[0] as [$number, $x]) {
                if (count($this->getAdjacentSymbols($x, $y, strlen($number), $symbols)) > 0) {
                    $numbers[] = (int) $number;
                }
            }
        }

        dump(array_sum($numbers));

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {
        $grid = Stream::of(file($input->getArgument('input')))->map('trim')->toArray();
        $symbols = $this->extractSymbols($grid);
        $adjacent_numbers = array_combine(array_keys($symbols), array_pad([], count($symbols), []));
        $numbers = [];

        foreach ($grid as $y => $line) {
            if (false === preg_match_all('/\d+/', $line, $matches, PREG_OFFSET_CAPTURE)) {
                continue;
            }

            foreach ($matches[0] as [$number, $x]) {
                foreach ($this->getAdjacentSymbols($x, $y, strlen($number), $symbols) as $name => $symbol) {
                    $adjacent_numbers[$name][] = (int) $number;
                }
            }
        }

        $adjacent_numbers = array_filter($adjacent_numbers, fn(array $numbers) => count($numbers) === 2);
        $ratios = array_map(fn(array $numbers) => $numbers[0] * $numbers[1], $adjacent_numbers);

        dump(array_sum($ratios));

        return self::SUCCESS;
    }

    private function extractSymbols(array $grid): array
    {
        $symbols = [];

        foreach ($grid as $y => $row) {
            for ($x = 0, $n = strlen($row); $x < $n; $x++) {
                $symbol = substr($row, $x, 1);
                if (1 !== preg_match('/[^.0-9]/', $symbol)) {
                    continue;
                }

                $symbols[sprintf('%d;%d', $x, $y)] = [$x, $y, $symbol];
            }
        }

        return $symbols;
    }

    private function getAdjacentSymbols(int $sx, int $y, int $length, array $symbols): array
    {
        $locations = [];
        for ($x = $sx - 1, $n = $sx + $length; $x <= $n; $x++) {
            array_push(
                $locations,
                [$x, $y - 1],
                [$x, $y],
                [$x, $y + 1],
            );
        }

        return array_filter($symbols, function (array $symbol) use ($locations) {
            return in_array([$symbol[0], $symbol[1]], $locations, true);
        });
    }
}
