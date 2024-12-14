<?php

namespace Janusqa\Adventofcode\Day14;

class Day14B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $RUBOUND = 7; //103;
        $CUBOUND = 11; //101;
        $pattern_robot = '/-?\d+/';
        $robots = [];
        // $grid = [];

        foreach ($lines as $line) {
            preg_match_all($pattern_robot, $line, $matches);
            $robots[] = array_map(fn($n) => (int)$n, $matches[0]);
        }

        for ($i = 0; $i < count($robots); $i++) {
            $robots[$i][0] = $this->wrap($robots[$i][0] + $robots[$i][2] * 100, $CUBOUND);
            $robots[$i][1] = $this->wrap($robots[$i][1] + $robots[$i][3] * 100, $RUBOUND);
            // $grid[$robots[$i][1]][$robots[$i][0]] = ($grid[$robots[$i][1]][$robots[$i][0]] ?? 0) + 1;
        }

        $xm = floor($CUBOUND / 2);
        $ym = floor($RUBOUND / 2);

        $safety_factor = 0;
        $quadrants = [];

        foreach ($robots as $robot) {
            if ($robot)
                $safety_factor += $robot[0] === $xm || $robot[1] === $ym ? 0 : 1;
        }

        // $this->printGrid($grid, $RUBOUND, $CUBOUND);

        echo $safety_factor . PHP_EOL;
    }


    private function wrap(int $index, int $wrapAt): int
    {
        return ($index % $wrapAt + $wrapAt) % $wrapAt;
    }



    // private function printGrid(array $grid, int $rows, int $cols): void
    // {
    //     for ($r = 0; $r < $rows; $r++) {
    //         for ($c = 0; $c < $cols; $c++) {
    //             echo isset($grid[$r][$c]) ? $grid[$r][$c] : '.'; // Print grid count or '.' if empty
    //         }
    //         echo PHP_EOL;
    //     }
    // }
}
