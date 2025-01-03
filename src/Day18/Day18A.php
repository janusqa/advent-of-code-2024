<?php

namespace Janusqa\Adventofcode\Day18;

class Day18A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $bytes = [];

        foreach ($lines as $line) {
            $bytes[] = array_map(fn($n) => (int)$n, explode(",", $line));
        }

        $RUBOUND = 70;
        $CUBOUND = 70;
        $BYTES_DROPED = 1024;

        $start = [0, 0];
        $end = [$RUBOUND, $CUBOUND];

        $grid = [];

        for ($i = 0; $i < $BYTES_DROPED; $i++) {
            [$x, $y] = $bytes[$i];

            if (!$this->OutOfBounds($y, $x, $RUBOUND, $CUBOUND)) {
                $grid[$y][$x] = '#';
            }
        }

        $steps = $this->bfs($grid, $RUBOUND, $CUBOUND, $start, $end);

        echo $steps . PHP_EOL;
    }

    // BFS 
    private function bfs(array $grid, int $RUBOUND, int $CUBOUND, array $start, array $end): int
    {

        $queue = new \SplQueue();
        $directions = ['^' => [-1, 0], '>' => [0, 1], 'V' => [1, 0], '<' => [0, -1]];
        $visited = [];
        $best = PHP_INT_MAX;

        $queue->enqueue(['p' => $start, 'd' => 0, 'dir' => null]);

        while (!$queue->isEmpty()) {

            $step = $queue->dequeue();

            [$r, $c] = $step['p'];
            $d = $step['d'];

            $vkey = "$r,$c";

            if (isset($visited[$vkey])) continue;

            $visited[$vkey] = true;


            if ($r === $end[0] && $c === $end[1]) {
                $best = min($d, $best);
            }

            foreach ($directions as $next_dir => $direction) {
                $next_r = $r + $direction[0];
                $next_c = $c + $direction[1];

                if ($this->OutOfBounds($next_r, $next_c, $RUBOUND, $CUBOUND)) continue;
                if (($grid[$next_r][$next_c] ?? '.') === "#") continue;

                $queue->enqueue(['p' => [$next_r, $next_c], 'd' => $d + 1, 'dir' => $next_dir]);
            }
        }

        return $best === PHP_INT_MAX ? -1 : $best;
    }

    private function OutOfBounds(int $row, int $col, int $RUBound, int $CUBound): bool
    {
        return (0 > $row || $row > $RUBound || 0 > $col || $col > $CUBound);
    }

    private function printGrid(array $grid, int $rows, int $cols, string $file = ""): void
    {
        $output = "";

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
