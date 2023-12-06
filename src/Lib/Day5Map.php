<?php
declare(strict_types=1);

namespace App\Lib;

class Day5Map
{
    private array $maps = [];

    public function addRange(int $source, int $destination, int $size): void
    {
        $this->maps[] = [
            $source, $source + $size - 1,
            $destination, $destination + $size - 1,
        ];
    }

    public function get(int $from): int
    {
        foreach ($this->maps as [$src_from, $src_to, $dest_from, $dest_to]) {
            if ($src_from <= $from && $from <= $src_to) {
                return $dest_from + ($from - $src_from);
            }
        }
        return $from;
    }

    public function chunks(): array
    {
        return array_merge(...$this->maps);
    }

    public function min()
    {
        $min = PHP_INT_MAX;

        foreach ($this->maps as [$src_from, $src_to, $dest_from, $dest_to]) {
            $min = min($min, $src_from, $dest_from);
        }

        return $min;
    }

    public function max()
    {
        $max = 0;

        foreach ($this->maps as [$src_from, $src_to, $dest_from, $dest_to]) {
            $max = max($max, $src_to, $dest_to);
        }

        return $max;
    }
}
