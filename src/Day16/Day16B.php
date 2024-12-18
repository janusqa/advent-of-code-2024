<?php

namespace Janusqa\Adventofcode\Day16;

class Day16B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

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

        $score = $this->dijkstra($grid, $start, $end);

        echo $score . PHP_EOL;
    }

    // Dijkstra's Algorithm for all shortest paths
    private function dijkstra(array $grid, array $start, array $end): int
    {
        $directions = ['N' => [-1, 0], 'E' => [0, 1], 'S' => [1, 0], 'W' => [0, -1]];

        $movements = new \SplPriorityQueue();
        $visited = [];
        $best_priority = PHP_INT_MAX;
        $backtrack = [];
        $goals = [];

        $movements->insert(['data' => [$start[0], $start[1], 'E'], 'priority' => 0], 0);

        while (!$movements->isEmpty()) {

            $step = $movements->extract();

            [$r, $c, $d] = $step['data'];
            $priority = $step['priority'];

            $vkey = implode(",", $step['data']);

            if ($priority > ($visited[$vkey] ?? PHP_INT_MAX)) continue;

            if ($r === $end[0] && $c === $end[1]) {
                if ($priority > $best_priority) break;
                $best_priority = $priority;
                $goals[$vkey] = true; // goal added multiple times as it can be approached form different directions
            }

            foreach ($directions as $next_d => $direction) {

                // prevent moving backwards
                if (isset($directions[$d]) && ($directions[$d][0] + $direction[0] === 0 && $directions[$d][1] + $direction[1] === 0)) continue;

                $next_r = $r + $direction[0];
                $next_c = $c + $direction[1];
                $next_vkey = implode(",", [$next_r, $next_c, $next_d]);

                // prevent hitting a wall
                if ($grid[$next_r][$next_c] === "#") continue;

                $p = $priority + 1 + ($d === $next_d ? 0 : 1000);
                $next_priority = $p < PHP_INT_MAX ? $p : PHP_INT_MAX;

                $best_next_priority = $visited[$next_vkey] ?? PHP_INT_MAX;
                if ($next_priority > $best_next_priority) continue;
                if ($next_priority < $best_next_priority) {
                    $backtrack[$next_vkey] = [];
                    $visited[$next_vkey] = $next_priority;
                }

                $backtrack[$next_vkey][$vkey] = true;

                $movements->insert(['data' => [$next_r, $next_c, $next_d], 'priority' => $next_priority], -$next_priority);
            }
        }

        // $filtered = array_filter($visited, function ($value, string $key) use ($end) {
        //     $vkey = unserialize($key);
        //     return $vkey[0] === $end[0] && $vkey[1] === $end[1];
        // }, ARRAY_FILTER_USE_BOTH);
        // $result = reset($filtered); // Get first value of filterd visited associated array

        return $this->bfs($backtrack, $goals);
    }

    //BFS
    private function bfs(array $backtrack, array $goals): int
    {
        // file_put_contents("output.txt", print_r($backtrack, true));

        $queue = new \SplQueue();

        foreach (array_keys($goals) as $goal) {
            $queue->enqueue($goal);
        }

        $visited = $goals;

        while (!$queue->isEmpty()) {

            $goal = $queue->dequeue();

            foreach (array_keys($backtrack[$goal] ?? []) as $tile) {
                if (isset($visited[$tile])) continue;
                $visited[$tile] = true;
                $queue->enqueue($tile);
            }
        }

        return count(array_unique(array_map(fn($n) => implode(",", array_slice(explode(",", $n), 0, 2)), array_keys($visited))));
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
