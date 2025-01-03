<?php

namespace Janusqa\Adventofcode\Day20;

class Day20B
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

        $data = $this->dijkstra($grid, $start, $end, 1);
        $path = array_reverse($data['path']);

        $cheats = [];

        $steps = array_keys($path);
        for ($i = 0; $i < count($steps) - 1; $i++) {
            for ($j = $i + 1; $j < count($steps); $j++) {
                $ipath = $path[$steps[$i]];
                $jpath = $path[$steps[$j]];

                $d = $this->manhattan(explode(",", $steps[$i]), explode(",", $steps[$j]));

                if ($d <= 20 && $jpath - $ipath > $d) {
                    $cheats[$jpath - $ipath - $d] = ($cheats[$jpath - $ipath - $d] ?? 0) + 1;
                }
            }
        }

        $result = array_sum(array_filter($cheats, function ($key) {
            return $key >= 100;
        }, ARRAY_FILTER_USE_KEY));


        echo $result . PHP_EOL;
    }

    // Dijkstra's Algorithm
    private function dijkstra(array $grid, array $start, array $end, int $cheated = 0): array
    {
        $directions = ['U' => [-1, 0], 'R' => [0, 1], 'D' => [1, 0], 'L' => [0, -1]];

        $movements = new \SplPriorityQueue();
        $best_priority = PHP_INT_MAX;
        $visited = [];
        $backtrack = [];
        $goals = [];

        $movements->insert(['data' => ['current' => [$start[0], $start[1]], 'parent' => null], 'priority' => 0], 0);

        while (!$movements->isEmpty()) {
            $step = $movements->extract();

            [$r, $c] = $step['data']['current'];
            $priority = $step['priority'];

            $vkey = "$r,$c";
            $pkey = isset($step['data']['parent']) ? "{$step['data']['parent'][0]},{$step['data']['parent'][1]}" : null;

            if ($priority > ($visited[$vkey] ?? PHP_INT_MAX)) continue;

            $visited[$vkey] = $priority;

            if ($r === $end[0] && $c === $end[1]) {
                if ($priority > $best_priority) break;
                $best_priority = $priority;
                $goals[$vkey] = $priority; // goal added multiple times as it can be approached form different directions
            }

            if (isset($pkey)) $backtrack[$vkey][$pkey] = true;

            foreach ($directions as $next_d => $direction) {

                // prevent moving backwards
                // if (isset($directions[$d]) && ($directions[$d][0] + $direction[0] === 0 && $directions[$d][1] + $direction[1] === 0)) continue;

                $next_r = $r + $direction[0];
                $next_c = $c + $direction[1];
                $next_vkey = "$next_r,$next_c";

                // prevent hitting a wall
                if ($grid[$next_r][$next_c] === "#") continue;

                $p = $priority + 1;
                $next_priority = $p < PHP_INT_MAX ? $p : PHP_INT_MAX;
                if ($next_priority > ($visited[$next_vkey] ?? PHP_INT_MAX)) continue;

                $movements->insert(['data' => ['current' => [$next_r, $next_c], 'parent' => [$r, $c]], 'priority' => $next_priority], -$next_priority);
            }
        }

        return ['path' => $this->bfs($backtrack, $goals, $best_priority), 'best' => $best_priority];
    }

    private function bfs(array $backtrack, array $goals): array
    {
        $queue = new \SplQueue();

        $queue->enqueue(array_keys($goals)[0]);

        $visited = $goals;

        while (!$queue->isEmpty()) {

            $tile = $queue->dequeue();

            foreach (array_keys($backtrack[$tile] ?? []) as $parent) {
                if (isset($visited[$parent])) continue;
                $visited[$parent] = $visited[$tile] - 1;
                $queue->enqueue($parent);
            }
        }

        return $visited;
    }

    private function manhattan(array $orig, array $dest): int
    {
        return abs($orig[0] - $dest[0]) + abs($orig[1] - $dest[1]);
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
