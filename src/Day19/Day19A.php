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
            $queue = new \SplQueue();
            $visited = [];

            foreach ($patterns as $pattern) {
                if (strpos($design, $pattern) === 0) {
                    $queue->enqueue($pattern);
                    $visited[$pattern] = true;
                }
            }

            while (!$queue->isEmpty()) {
                $current_candidate = $queue->dequeue();

                if ($current_candidate === $design) {
                    $possible_designs++;
                    break;
                }

                foreach ($patterns as $pattern) {
                    $new_candidate = $current_candidate . $pattern;

                    if (strpos($design, $new_candidate) === 0 && !isset($visited[$new_candidate])) {
                        $queue->enqueue($new_candidate);
                        $visited[$new_candidate] = true;
                    }
                }
            }
        }

        echo $possible_designs . PHP_EOL;
    }
}
