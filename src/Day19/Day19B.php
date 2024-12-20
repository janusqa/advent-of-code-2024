<?php

namespace Janusqa\Adventofcode\Day19;

class Day19B
{
    public function run(string $input): void
    {
        [$p, $d] = explode("\n\n", trim($input));

        $patterns = explode(", ", $p);
        $designs = explode("\n", $d);

        $possible_designs = 0;

        foreach ($designs as $design) {
            $cache = [];
            $possible_designs += $this->possibilities($design, $patterns, $cache);
        }

        echo $possible_designs . PHP_EOL;
    }

    // Recursion
    private function possibilities(string $design, array $patterns, array &$cache): int
    {

        if (isset($cache[$design])) return $cache[$design];

        if (empty($design)) return 1;

        $possibilities = 0;

        foreach ($patterns as $pattern) {
            if (strpos($design, $pattern) === 0) {
                $possibilities += $this->possibilities(substr($design, strlen($pattern)), $patterns, $cache);
            }
        }

        $cache[$design] = $possibilities;
        return $possibilities;
    }
}
