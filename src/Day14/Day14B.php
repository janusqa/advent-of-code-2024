<?php

namespace Janusqa\Adventofcode\Day14;

class Day14B
{
    private $file = "./output.txt";

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $RUBOUND = 103;
        $CUBOUND = 101;
        $pattern_robot = '/-?\d+/';
        $robots = [];

        foreach ($lines as $line) {
            preg_match_all($pattern_robot, $line, $matches);
            $robots[] = array_map(fn($n) => (int)$n, $matches[0]);
        }

        $xm = (int)floor($CUBOUND / 2);
        $ym = (int)floor($RUBOUND / 2);

        $seconds = 0;
        $seen = [];

        while (true) {
            $grid = [];
            // $quadrants = [];

            for ($i = 0; $i < count($robots); $i++) {
                $next_x = $this->wrap($robots[$i][0] + ($robots[$i][2] * $seconds), $CUBOUND);
                $next_y = $this->wrap($robots[$i][1] + ($robots[$i][3] * $seconds), $RUBOUND);

                // if ($this->InBounds($robots[$i][1], $robots[$i][0], 0, 0, $ym - 1, $xm - 1)) {
                //     $quadrants['q1'] = ($quadrants['q1'] ?? 0) + 1;
                // } elseif ($this->InBounds($robots[$i][1], $robots[$i][0], 0, $xm + 1, $ym - 1, $CUBOUND - 1)) {
                //     $quadrants['q2'] = ($quadrants['q2'] ?? 0) + 1;
                // } elseif ($this->InBounds($robots[$i][1], $robots[$i][0], $ym + 1, 0, $RUBOUND - 1, $xm - 1)) {
                //     $quadrants['q3'] = ($quadrants['q3'] ?? 0) + 1;
                // } elseif ($this->InBounds($robots[$i][1], $robots[$i][0], $ym + 1, $xm + 1, $RUBOUND - 1, $CUBOUND - 1)) {
                //     $quadrants['q4'] = ($quadrants['q4'] ?? 0) + 1;
                // }

                $grid[$next_y][$next_x] = ($grid[$next_y][$next_x] ?? 0) + 1;
            }

            $grid_key  = serialize($grid);
            if (isset($seen[$grid_key])) {
                echo "Robot rotation repetition detected at $seen[$grid_key]";
                break;
            };
            $seen[$grid_key] = $seconds;

            $this->printGrid($grid, $RUBOUND, $CUBOUND, $seconds);

            // $safety_factor = 1;
            // foreach ($quadrants as $quadrant) {
            //     $safety_factor *= $quadrant;
            // }

            $seconds++;
        }

        // echo $seconds . PHP_EOL;
    }

    private function wrap(int $index, int $wrapAt): int
    {
        return ($index % $wrapAt + $wrapAt) % $wrapAt;
    }

    // private function InBounds(int $row, int $col, int $r_start, int $c_start, int $r_end, int $c_end): bool
    // {
    //     return ($r_start <= $row && $row <= $r_end && $c_start <= $col && $col <= $c_end);
    // }

    private function printGrid(array $grid, int $rows, int $cols, int $seconds = 0): void
    {
        file_put_contents($this->file, "Seconds: $seconds" . PHP_EOL, FILE_APPEND);
        // echo "Seconds: $seconds" .PHP_EOL;
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                file_put_contents($this->file, isset($grid[$r][$c]) ? $grid[$r][$c] : '.', FILE_APPEND); // Print grid count or '.' if empty
                // echo isset($grid[$r][$c]) ? $grid[$r][$c] : '.'; // Print grid count or '.' if empty
            }
            file_put_contents($this->file, PHP_EOL, FILE_APPEND);
            // echo PHP_EOL;
        }
        file_put_contents($this->file, PHP_EOL . PHP_EOL, FILE_APPEND);
        // echo PHP_EOL . PHP_EOL;
    }
}
