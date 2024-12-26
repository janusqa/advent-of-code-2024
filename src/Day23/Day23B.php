<?php

namespace Janusqa\Adventofcode\Day23;

class Day23B
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $edges = array_map(fn($n) => explode('-', $n), $lines);

        $graph = [];

        foreach ($edges as $edge) {
            $graph[$edge[0]][$edge[1]] = $edge[1];
            $graph[$edge[1]][$edge[0]] = $edge[0];
        }

        $password = array_reduce(
            $this->bronKerbosch([], array_keys($graph), [], $graph),
            function ($max, $clique) {
                return count($clique) > count($max) ? $clique : $max;
            },
            []
        );

        sort($password);

        echo implode(',', $password) . PHP_EOL;
    }


    function bronKerbosch(array $R, array $P, array $X, array $graph): array
    {
        $cliques = [];

        // Base case: If both P and X are empty, report R as a maximal clique
        if (empty($P) && empty($X)) {
            return [$R]; // Output the maximal clique
        }

        // Choose a pivot vertex u from P ⋃ X
        $pivot = null;
        $union = array_merge($P, $X); // Combine P and X
        if (!empty($union)) {
            $pivot = $union[0]; // Select the first vertex as pivot (simplified choice)
        }

        // Iterate over vertices in P \ N(u)
        foreach ($P as $v) {
            if ($pivot !== null && in_array($v, $graph[$pivot])) {
                continue; // Skip neighbors of pivot
            }

            // Recursive call with updated sets
            $nc = $this->bronKerbosch(
                array_merge($R, [$v]), // Add v to R
                array_intersect($P, $graph[$v]), // P ⋂ N(v)
                array_intersect($X, $graph[$v]), // X ⋂ N(v)
                $graph
            );

            // Merge the results into the cliques array
            $cliques = array_merge($cliques, $nc);

            // Move v from P to X
            $P = array_diff($P, [$v]);
            $X = array_merge($X, [$v]);
        }

        return $cliques;
    }
}
