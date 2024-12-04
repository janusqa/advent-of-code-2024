<?php

namespace Janusqa\Adventofcode\Day04;

class Day04A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $directions = [
            "nw" => [-1, -1],
            "n" => [-1, 0],
            "ne" => [-1, 1],
            "e" => [0, 1],
            "se" => [1, 1],
            "s" => [1, 0],
            "sw" => [1, -1],
            "w" => [0, -1],
        ];

        $word = "XMAS";
        $word_count = 0;

        $grid = [];

        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }

        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        foreach ($grid as $row_index => $row) {
            foreach ($row as $col_index => $col) {
                if ($col === 'X') {
                    foreach ($directions as $direction) {
                        $search = [];
                        $search[] = $col;
                        $row_temp = $row_index;
                        $col_temp = $col_index;
                        if (!$this->OutOfBounds($row_temp + (3 * $direction[0]), $col_temp + (3 * $direction[1]), $RUBound, $CUBound)) {
                            for ($i = 0; $i < 3; $i++) {
                                $row_temp += $direction[0];
                                $col_temp += $direction[1];
                                $search[] = $grid[$row_temp][$col_temp];
                            }
                            if ($word === implode('', $search)) {
                                $word_count++;
                            }
                        }
                    }
                }
            }
        }

        echo ($word_count . PHP_EOL);
    }

    private function OutOfBounds(int $row, int $col, int $URBound, int $CUBound): bool
    {
        return (0 > $row || $row > $URBound || 0 > $col || $col > $CUBound);
    }
}
