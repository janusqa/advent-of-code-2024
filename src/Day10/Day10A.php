<?php

namespace Janusqa\Adventofcode\Day10;

class Day10A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $grid = [];

        $directions = [[-1, 0], [0, 1], [1, 0], [0, -1]];

        foreach ($lines as $line) {
            $grid[] = array_map(fn($n) => $n === '.' ? null : (int)$n, str_split($line));
        }

        $sum_scores = 0;


        foreach ($grid as $r_idx => $row) {
            foreach ($row as $c_idx => $col) {
                if ($col === 0) {
                    $score = 0;
                    $visited = [];
                    $this->getScore($grid, $directions, $visited, $r_idx, $c_idx, $col, $score);
                    $sum_scores += $score;
                }
            }
        }

        echo $sum_scores . PHP_EOL;
    }

    private function getScore(array $grid, array $directions, array &$visited, int $r_idx, int $c_idx, int $level, int &$score): void
    {
        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        $key = implode(',', [$r_idx, $c_idx]);

        if ($level === 9 && $grid[$r_idx][$c_idx] === 9 && !in_array($key, $visited)) {
            $score++;
            $visited[] = $key;
            return;
        }

        foreach ($directions as $direction) {
            $newr_idx = $r_idx + $direction[0];
            $newc_idx = $c_idx + $direction[1];
            if (!$this->OutOfBounds($newr_idx, $newc_idx, $RUBound, $CUBound) && $grid[$newr_idx][$newc_idx] === $grid[$r_idx][$c_idx] + 1) {
                $this->getScore($grid, $directions, $visited, $newr_idx, $newc_idx, $level + 1, $score);
            }
        }

        return;
    }

    private function OutOfBounds(int $row, int $col, int $RUBound, int $CUBound): bool
    {
        return (0 > $row || $row > $RUBound || 0 > $col || $col > $CUBound);
    }
}
