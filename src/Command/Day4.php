<?php
declare(strict_types=1);

namespace App\Command;

use App\Lib\Day4Card;
use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'day4')]
class Day4 extends Command
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
        $total = Stream::of(file($input->getArgument('input')))
            ->map(fn(string $line) => Day4Card::fromLine($line))
            ->map(fn(Day4Card $card) => $card->wins === 0 ? 0 : pow(2, $card->wins - 1))
            ->reduce(fn (int $carry, int $points) => $carry + $points, 0);

        dump($total);

        return self::SUCCESS;
    }

    protected function part2(InputInterface $input, OutputInterface $output): int
    {
        /** @var Day4Card[] $cards */
        $cards = Stream::of(file($input->getArgument('input')))
            ->map(fn(string $line) => Day4Card::fromLine($line))
            ->toArray();

        $wins = array_fill(0, count($cards), 0);
        $card_wins = array_fill(0, count($cards), 0);

        for ($i = count($wins) - 1; $i >= 0; $i--) {
            $wins_for_this_card = [$i];

            for ($j = $i + 1; $j < count($wins) && $j <= $i + $cards[$i]->wins; $j++) {
                array_push($wins_for_this_card, ...$card_wins[$j]);
            }

            $card_wins[$i] = $wins_for_this_card;
        }

        foreach (array_keys($wins) as $i) {
            foreach ($card_wins[$i] as $card) {
                $wins[$card]++;
            }
        }

        dump(array_sum($wins));

        return self::SUCCESS;
    }
}
