<?php

namespace Janusqa\Adventofcode\Day15;

class Day15A
{

    public function run(string $input): void
    {
        $lines = explode("\n\n", trim($input));

        $rows = explode("\n", $lines[0]);
        $RUBOUND = count($rows) - 1;
        $CUBOUND =  strlen($rows[0]) - 1;
        $grid = array_map(fn($n) => str_split($n), $rows);
        $instructions = str_split(str_replace(["\n", "\r"], "", $lines[1]));

        $robot = [];
        foreach ($grid as $r_idx => $row) {
            foreach ($row as $c_idx => $col) {
                if ($col === '@') {
                    $robot =  [$r_idx, $c_idx];
                    break 2;
                }
            }
        }

        $directions = [
            "^" => [-1, 0],
            ">" => [0, 1],
            "v" => [1, 0],
            "<" => [0, -1],
        ];

        foreach ($instructions as $instruction) {
            $next_r = $robot[0] + $directions[$instruction][0];
            $next_c = $robot[1] + $directions[$instruction][1];
            if ($this->canMove($grid, $directions, $instruction, [$next_r, $next_c])) {
                $grid[$next_r][$next_c] = $grid[$robot[0]][$robot[1]];
                $grid[$robot[0]][$robot[1]] = '.';
                $robot = [$next_r, $next_c];
            }
        }

        $total = 0;
        for ($r = 0; $r <= $RUBOUND; $r++) {
            for ($c = 0; $c <= $CUBOUND; $c++) {
                if ($grid[$r][$c] === 'O') {
                    $total += ($r * 100) + $c;
                }
            }
        }

        echo $total . PHP_EOL;
    }

    private function canMove(array &$grid, array $directions, string $instruction, array $location): bool
    {
        if ($grid[$location[0]][$location[1]] === '#') {
            return false;
        } elseif ($grid[$location[0]][$location[1]] === '.') {
            return true;
        }

        $next_r = $location[0] + $directions[$instruction][0];
        $next_c = $location[1] + $directions[$instruction][1];

        if ($this->canMove($grid, $directions, $instruction, [$next_r, $next_c])) {
            $grid[$next_r][$next_c] = $grid[$location[0]][$location[1]];
            $grid[$location[0]][$location[1]] = '.';
            return true;
        }

        return false;
    }

    private function printGrid(array $grid, int $rows, int $cols, string $instruction = "", string $file = ""): void
    {
        $output = "Move: $instruction" . PHP_EOL;

        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                $output .= isset($grid[$r][$c]) ? $grid[$r][$c] : '.';
            }
            $output .= PHP_EOL;
        }

        $output .= PHP_EOL;

        if (!empty($file)) {
            file_put_contents($file, $output, FILE_APPEND);
        } else {
            echo $output;
        }
    }
}
