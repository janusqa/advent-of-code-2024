<?php

namespace Janusqa\Adventofcode\Day16;

class Day16A
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

    // Dijkstra's Algorithm
    private function dijkstra(array $grid, array $start, array $end): int
    {
        $directions = ['N' => [-1, 0], 'E' => [0, 1], 'S' => [1, 0], 'W' => [0, -1]];

        $movements = new \SplPriorityQueue();
        $visited = [];

        $movements->insert(['data' => [$start[0], $start[1], 'E'], 'priority' => 0], 0);

        while (!$movements->isEmpty()) {
            $step = $movements->extract();

            [$r, $c, $d] = $step['data'];
            $priority = $step['priority'];

            $vkey = implode(",", $step['data']);

            if (isset($visited[$vkey]))  continue;

            $visited[$vkey] = $priority;

            if ($r === $end[0] && $c === $end[1]) return $priority;


            foreach ($directions as $next_d => $direction) {

                // prevent moving backwards
                if (isset($directions[$d]) && ($directions[$d][0] + $direction[0] === 0 && $directions[$d][1] + $direction[1] === 0)) continue;

                $next_r = $r + $direction[0];
                $next_c = $c + $direction[1];

                // prevent hitting a wall
                if ($grid[$next_r][$next_c] === "#") continue;

                $p = $priority + 1 + ($d === $next_d ? 0 : 1000);
                $next_priority = $p < PHP_INT_MAX ? $p : PHP_INT_MAX;
                $movements->insert(['data' => [$next_r, $next_c, $next_d], 'priority' => $next_priority], -$next_priority);
            }
        }

        // $filtered = array_filter($visited, function ($value, string $key) use ($end) {
        //     $vkey = unserialize($key);
        //     return $vkey[0] === $end[0] && $vkey[1] === $end[1];
        // }, ARRAY_FILTER_USE_BOTH);
        // $result = reset($filtered); // Get first value of filterd visited associated array

        return -1;
    }
}
