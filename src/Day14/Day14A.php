<?php

namespace Janusqa\Adventofcode\Day14;

class Day14A
{

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
        $quadrants = [];

        for ($i = 0; $i < count($robots); $i++) {
            $robots[$i][0] = $this->wrap($robots[$i][0] + ($robots[$i][2] * 100), $CUBOUND);
            $robots[$i][1] = $this->wrap($robots[$i][1] + ($robots[$i][3] * 100), $RUBOUND);

            if ($this->InBounds($robots[$i][1], $robots[$i][0], 0, 0, $ym - 1, $xm - 1)) {
                $quadrants['q1'] = ($quadrants['q1'] ?? 0) + 1;
            } elseif ($this->InBounds($robots[$i][1], $robots[$i][0], 0, $xm + 1, $ym - 1, $CUBOUND - 1)) {
                $quadrants['q2'] = ($quadrants['q2'] ?? 0) + 1;
            } elseif ($this->InBounds($robots[$i][1], $robots[$i][0], $ym + 1, 0, $RUBOUND - 1, $xm - 1)) {
                $quadrants['q3'] = ($quadrants['q3'] ?? 0) + 1;
            } elseif ($this->InBounds($robots[$i][1], $robots[$i][0], $ym + 1, $xm + 1, $RUBOUND - 1, $CUBOUND - 1)) {
                $quadrants['q4'] = ($quadrants['q4'] ?? 0) + 1;
            }
        }

        $safety_factor = 1;

        foreach ($quadrants as $quadrant) {
            $safety_factor *= $quadrant;
        }

        echo $safety_factor . PHP_EOL;
    }

    private function wrap(int $index, int $wrapAt): int
    {
        return ($index % $wrapAt + $wrapAt) % $wrapAt;
    }

    private function InBounds(int $row, int $col, int $r_start, int $c_start, int $r_end, int $c_end): bool
    {
        return ($r_start <= $row && $row <= $r_end && $c_start <= $col && $col <= $c_end);
    }
}
