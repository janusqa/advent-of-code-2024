<?php

namespace Janusqa\Adventofcode\Day20;

class Day20A
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $RUBOUND = count($lines) - 1;
        $CUBOUND = strlen($lines[0]) - 1;
        $grid = [];
        $start = [];
        $end = [];

        foreach ($lines as $r_idx => $row) {
            $startpos = strpos($row, 'S');
            $endpos = strpos($row, 'E');
            if ($startpos !== false) $start = [$r_idx, $startpos];
            if ($endpos !== false) $end = [$r_idx, $endpos];
            $grid[] = str_split($row);
        }

        $bestime_without_cheats = $this->dijkstra($grid, $start, $end, 1)[0];

        $times  = array_reduce(
            $this->dijkstra($grid, $start, $end),
            function ($carry, $n) use ($bestime_without_cheats) {
                $carry[$bestime_without_cheats - $n] = ($carry[$bestime_without_cheats - $n] ?? 0) + 1; // Map key to value
                return $carry;
            },
            []
        );

        $cheats = array_sum(array_filter($times, function ($key) {
            return $key >= 100;
        }, ARRAY_FILTER_USE_KEY));


        echo $cheats . PHP_EOL;
    }

    // Dijkstra's Algorithm
    private function dijkstra(array $grid, array $start, array $end, int $cheated = 0): array
    {
        $directions = ['U' => [-1, 0], 'R' => [0, 1], 'D' => [1, 0], 'L' => [0, -1]];

        $movements = new \SplPriorityQueue();
        $visited = [];
        $times = [];

        $movements->insert(['data' => [$start[0], $start[1]], 'priority' => 0], 0);

        while (!$movements->isEmpty()) {
            $step = $movements->extract();

            [$r, $c] = $step['data'];
            $priority = $step['priority'];

            $vkey = "$r,$c";

            if (isset($visited[$vkey]))  continue;

            $visited[$vkey] = $priority;

            if ($r === $end[0] && $c === $end[1]) {
                return [...$times, $priority];
            }


            foreach ($directions as $next_d => $direction) {

                // prevent moving backwards
                // if (isset($directions[$d]) && ($directions[$d][0] + $direction[0] === 0 && $directions[$d][1] + $direction[1] === 0)) continue;

                $next_r = $r + $direction[0];
                $next_c = $c + $direction[1];

                // prevent hitting a wall
                if ($grid[$next_r][$next_c] === "#") {
                    if (!$this->OutOfBounds($next_r, $next_c, count($grid) - 2, count($grid[0]) - 2, 1, 1) && $cheated === 0) {
                        $cheating_grid = $grid;
                        $cheating_grid[$next_r][$next_c] = ".";
                        $times = [...$times, ...array_map(fn($n) => $n + $priority, $this->dijkstra($cheating_grid, [$r, $c], $end, $cheated + 1))];
                    }

                    continue;
                };

                $p = $priority + 1;
                $next_priority = $p < PHP_INT_MAX ? $p : PHP_INT_MAX;
                $movements->insert(['data' => [$next_r, $next_c], 'priority' => $next_priority], -$next_priority);
            }
        }

        return [];
    }

    private function OutOfBounds(int $row, int $col, int $RUBound, int $CUBound, int $RLBound = 0, int $CLBound = 0): bool
    {
        return ($RLBound > $row || $row > $RUBound || $CLBound > $col || $col > $CUBound);
    }

    private function printGrid(array $grid, string $file = ""): void
    {
        $rows = count($grid);
        $cols = count($grid[0]);
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
