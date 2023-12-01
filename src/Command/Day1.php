<?php
declare(strict_types=1);

namespace App\Command;

use phpstream\Stream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:day1')]
class Day1 extends Command
{
    private array $number_mapping = [
        'one' => '1',
        'two' => '2',
        'three' => '3',
        'four' => '4',
        'five' => '5',
        'six' => '6',
        'seven' => '7',
        'eight' => '8',
        'nine' => '9',
    ];

    protected function configure(): void
    {
        $this->addArgument("input", InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $total = Stream::of(file($input->getArgument('input')))
            ->map(fn (string $line) => $this->extractDigits($line))
            ->map(fn (array $digits) => (int) ($digits[0] . $digits[count($digits) - 1]))
            ->reduce(fn (int $carry, int $number) => $carry + $number, 0);

        var_dump($total);

        return self::SUCCESS;
    }

    private function extractDigits(string $line): array
    {
        $digits = [];

        for ($i = 0, $n = strlen($line); $i < $n; $i++) {
            if (ctype_digit($line[$i])) {
                $digits[] = $line[$i];
            } else {
                foreach ($this->number_mapping as $name => $digit) {
                    if (str_starts_with(substr($line, $i), $name)) {
                        $digits[] = $digit;
                        break;
                    }
                }
            }
        }

        return $digits;
    }
}
