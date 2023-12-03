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
        return $this->part1($input, $output);
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
                if ($this->hasAdjacentSymbols($x, $y, strlen($number), $symbols)) {
                    $numbers[] = (int) $number;
                }
            }
        }

        dump(array_sum($numbers));

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {

        return self::SUCCESS;
    }

    private function extractSymbols(array $grid): array
    {
        $symbols = [];

        foreach ($grid as $y => $row) {
            for ($x = 0, $n = strlen($row); $x < $n; $x++) {
                if (1 !== preg_match('/[^.0-9]/', substr($row, $x, 1))) {
                    continue;
                }

                $symbols[] = [$x, $y];
            }
        }

        return $symbols;
    }

    private function hasAdjacentSymbols(int $sx, int $y, int $length, array $symbols): bool
    {
        for ($x = $sx - 1, $n = $sx + $length; $x <= $n; $x++) {
            if (in_array([$x, $y - 1], $symbols, true)
                || in_array([$x, $y], $symbols, true)
                || in_array([$x, $y + 1], $symbols, true)
            ) {
                return true;
            }
        }

        return false;
    }
}
