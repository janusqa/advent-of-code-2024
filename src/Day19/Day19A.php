<?php

namespace Janusqa\Adventofcode\Day19;

class Day19A
{
    public function run(string $input): void
    {
        [$p, $d] = explode("\n\n", trim($input));

        $patterns = explode(", ", $p);
        $designs = explode("\n", $d);

        $possible_designs = 0;

        foreach ($designs as $design) {
            $cache = [];
            $possible_designs += $this->isPossibleRecursion($design, $patterns, $cache) ? 1 : 0;
        }

        echo $possible_designs . PHP_EOL;
    }

    //BFS
    private function isPossibleBFS(string $design, array $patterns, array &$visited): bool
    {
        $queue = new \SplQueue();

        foreach ($patterns as $pattern) {
            if (strpos($design, $pattern) === 0) {
                $queue->enqueue($pattern);
                $visited[$pattern] = true;
            }
        }

        while (!$queue->isEmpty()) {
            $current_candidate = $queue->dequeue();

            if ($current_candidate === $design) {
                return true;
            }

            foreach ($patterns as $pattern) {
                $new_candidate = $current_candidate . $pattern;

                if (strpos($design, $new_candidate) === 0 && !isset($visited[$new_candidate])) {
                    $queue->enqueue($new_candidate);
                    $visited[$new_candidate] = true;
                }
            }
        }

        return false;
    }

    // Recursion
    private function isPossibleRecursion(string $design, array $patterns, array &$cache): bool
    {

        if (isset($cache[$design])) return $cache[$design];

        if (empty($design)) return true;

        foreach ($patterns as $pattern) {
            if (strpos($design, $pattern) === 0 && $this->isPossibleRecursion(substr($design, strlen($pattern)), $patterns, $cache)) {
                $cache[$design] = true;
                return true;
            }
        }

        $cache[$design] = false;
        return false;
    }
}
