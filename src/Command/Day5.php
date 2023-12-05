<?php
declare(strict_types=1);

namespace App\Command;

use App\Lib\Day5Map;
use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'day5')]
class Day5 extends Command
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
        $lines = file($input->getArgument('input'));
        $maps = [
            'seed-to-soil' => new Day5Map(),
            'soil-to-fertilizer' => new Day5Map(),
            'fertilizer-to-water' => new Day5Map(),
            'water-to-light' => new Day5Map(),
            'light-to-temperature' => new Day5Map(),
            'temperature-to-humidity' => new Day5Map(),
            'humidity-to-location' => new Day5Map(),
        ];
        $seeds = Stream::of(
                array_map('intval', explode(' ', substr(array_shift($lines), 7))),
        );
        $type = null;

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];

            if (empty(trim($line))) {
                $i++;
                $type = explode(' ', $lines[$i])[0];
            } else {
                $input = array_map('intval', explode(' ', trim($line)));
                $maps[$type]->addRange($input[0], $input[1], $input[2]);
            }
        }

        $total = $seeds
            ->map(fn(int $seed) => $maps['seed-to-soil']->get($seed))
            ->map(fn(int $seed) => $maps['soil-to-fertilizer']->get($seed))
            ->map(fn(int $seed) => $maps['fertilizer-to-water']->get($seed))
            ->map(fn(int $seed) => $maps['water-to-light']->get($seed))
            ->map(fn(int $seed) => $maps['light-to-temperature']->get($seed))
            ->map(fn(int $seed) => $maps['temperature-to-humidity']->get($seed))
            ->map(fn(int $seed) => $maps['humidity-to-location']->get($seed))
            ->reduce(fn (int $carry, int $location) => min($carry, $location), PHP_INT_MAX)
        ;

        dump($total);

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {

        return self::SUCCESS;
    }
}
