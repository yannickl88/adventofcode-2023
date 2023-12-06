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

    public function merge(Day5Map $map): Day5Map
    {
        $dest_ranges = [];
        $src_ranges = [];

        foreach ($map->maps as [$src_from, $src_to, $dest_from, $dest_to]) {
            $dest_ranges[] = [$dest_from, $dest_to];
        }
        foreach ($this->maps as [$src_from, $src_to, $dest_from, $dest_to]) {
            $src_ranges[] = [$src_from, $src_to];
        }

        usort($dest_ranges, fn(array $a, array $b) => $a[0] <=> $b[0]);
        usort($src_ranges, fn(array $a, array $b) => $a[0] <=> $b[0]);

        $intersections = [];
        $dest = 0;
        $src = 0;

        dump($dest_ranges, $src_ranges);

        $iterations = 0;

        while ($dest < count($dest_ranges) && $src < count($src_ranges)) {
            $dest_range = $dest_ranges[$dest];
            $src_range = $src_ranges[$src];

            if ($dest_range[0] === $src_range[0]) {
                if ($dest_range[1] === $src_range[1]) {
                    $intersections[] = $dest_range;
                    $dest++;
                    $src++;
                } elseif ($dest_range[1] < $src_range[1]) {
                    $intersections[] = $dest_range;
                    $dest++;
                } else {
                    $intersections[] = $src_range;
                    $src++;
                }
            } elseif ($dest_range[0] < $src_range[0]) {
                if ($dest_range[1] < $src_range[0]) {
                    $intersections[] = $dest_range;
                    $dest++;
                }
            } else {
                if ($src_range[1] < $dest_range[0]) {
                    $intersections[] = $src_range;
                    $src++;
                } elseif ($dest_range[0] < $src_range[1]) {
                    $intersections[] = [$src_range[0], $dest_range[0]];
                    $intersections[] = [$dest_range[1], $src_range[1]];
                    $dest++;
                    $src++;
                }
            }

            if ($iterations === 2) {
                break;
            }
            $iterations++;
        }

        dump($intersections);

        return new Day5Map();
    }
}
