<?php

namespace Janusqa\Adventofcode\Day23;

class Day23A
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

        $groups = [];
        foreach ($graph as $n0 => $node) {
            foreach (array_slice($node, 0, count($node) - 1) as $n1) {
                foreach (array_slice($node, 1, count($node) - 1) as $n2) {
                    if (isset($graph[$n1][$n2]) || isset($graph[$n2][$n1])) {
                        $group = [$n0, $n1, $n2];
                        sort($group);
                        $gkey = implode(',', $group);
                        if (!isset($groups[$gkey])) {
                            $groups[$gkey] = $group;
                        }
                    }
                }
            }
        }

        $sets = 0;
        foreach ($groups as $group) {
            foreach ($group as $node) {
                if (strpos($node, 't') === 0) {
                    $sets++;
                    break;
                }
            }
        }

        echo $sets . PHP_EOL;
    }
}
