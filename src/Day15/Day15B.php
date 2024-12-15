<?php

namespace Janusqa\Adventofcode\Day15;

class Day15B
{
    private $file = "output.txt";

    public function run(string $input): void
    {
        $lines = explode("\n\n", trim($input));


        $rows = explode("\n", $lines[0]);
        $RUBOUND = count($rows) - 1;
        $CUBOUND =  strlen($rows[0]) - 1;
        $grid = array_map(fn($n) => str_split($n), $rows);
        $instructions = str_split(str_replace(["\n", "\r"], "", $lines[1]));

        $grid = $this->upscale($grid);
        $RUBOUND = count($grid) - 1;
        $CUBOUND =  count($grid[0]) - 1;

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

        // $this->printGrid($grid, count($grid), count($grid[0]));
        // print_r($instructions);
        // print_r($robot);

        // $this->printGrid($grid, count($grid), count($grid[0]), "");
        foreach ($instructions as $instruction) {
            $next_r = $robot[0] + $directions[$instruction][0];
            $next_c = $robot[1] + $directions[$instruction][1];
            if ($this->canMove($grid, $directions, $instruction, [$next_r, $next_c])) {
                $grid[$next_r][$next_c] = $grid[$robot[0]][$robot[1]];
                $grid[$robot[0]][$robot[1]] = '.';
                $robot = [$next_r, $next_c];
            }
            $this->printGrid($grid, count($grid), count($grid[0]), $instruction);
        }
        // $this->printGrid($grid, count($grid), count($grid[0]), $instruction);

        $total = 0;
        foreach ($grid as $r_idx => $row) {
            foreach ($row as $c_idx => $col) {
                if ($col === '[') {
                    $total += ($r_idx * 100) + $c_idx;
                }
            }
        }

        echo $total . PHP_EOL;
    }

    private function canMove(array &$grid, array $directions, string $instruction, array $location): bool
    {
        $isVertical = $instruction === '^' || $instruction === 'v';

        if ($grid[$location[0]][$location[1]] === '#') {
            return false;
        } elseif ($grid[$location[0]][$location[1]] === '.') {
            return true;
        }

        $next_r1 = $location[0] + $directions[$instruction][0];
        $next_c1 = $location[1] + $directions[$instruction][1];

        if ($isVertical) {
            $next_r2 = $next_r1;
            $next_c2 = ($grid[$location[0]][$location[1]] === ']' ? $location[1] - 1 : $location[1] + 1) + $directions[$instruction][1];
        }

        if ($this->canMove($grid, $directions, $instruction, [$next_r1, $next_c1]) && ($isVertical ? $this->canMove($grid, $directions, $instruction, [$next_r2, $next_c2]) : true)) {
            if ($isVertical) {
                $c2 = ($grid[$location[0]][$location[1]] === ']' ? $location[1] - 1 : $location[1] + 1);
                $grid[$next_r2][$next_c2] = $grid[$location[0]][$c2];
                $grid[$location[0]][$c2] = '.';
            }
            $grid[$next_r1][$next_c1] = $grid[$location[0]][$location[1]];
            $grid[$location[0]][$location[1]] = '.';
            // $this->printGrid($grid, count($grid), count($grid[0]), $instruction);
            return true;
        } else {
            // $this->printGrid($grid, count($grid), count($grid[0]), $instruction);
        }

        return false;
    }

    private function upscale(array $grid): array
    {
        $new_grid = [];

        foreach ($grid as $r_idx => $row) {
            $new_row = [];
            foreach ($row as $c_idx => $col) {
                if ($col === 'O') {
                    $new_row[] = '[';
                    $new_row[] = ']';
                } elseif ($col === '@') {
                    $new_row[] = '@';
                    $new_row[] = '.';
                } else {
                    $new_row[] = $col;
                    $new_row[] = $col;
                }
            }
            $new_grid[] = $new_row;
        }

        return $new_grid;
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
