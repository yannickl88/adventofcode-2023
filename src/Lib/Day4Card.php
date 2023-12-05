<?php
declare(strict_types=1);

namespace App\Lib;
class Day4Card
{
    public array $winning_numbers;
    public int $wins;

    public function __construct(public array $winning, public array $numbers)
    {
        $this->winning_numbers = array_intersect($winning, $numbers);
        $this->wins = count($this->winning_numbers);
    }

    public static function fromLine(string $line): self
    {
        if (1 !== preg_match('/Card +(\d+):(( +\d+)*) \|(( +\d+)*)/', $line, $matches)) {
            dump($line);
        }

        return new self(
            array_map('intval', preg_split('/ +/', trim($matches[2]))),
            array_map('intval', preg_split('/ +/', trim($matches[4]))),
        );
    }
}
