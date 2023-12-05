<?php
declare(strict_types=1);

namespace App\Lib;

class Day5Map
{
    private array $maps = [];

    public function addRange(int $source, int $destination, int $size): void
    {
        $this->maps[] = [
            $destination, $destination + $size - 1,
            $source, $source + $size - 1
        ];
    }

    public function get(int $from): int
    {
        foreach ($this->maps as [$dest_from, $dest_to, $src_from, $src_to]) {
            if ($dest_from <= $from && $from <= $dest_to) {
                return $src_from + ($from - $dest_from);
            }
        }
        return $from;
    }
}
