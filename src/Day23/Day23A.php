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
        foreach ($graph as $node => $neighbours) {
            $adj_list = array_keys($neighbours);
            for ($i = 0; $i < count($adj_list) - 1; $i++) {
                $n1 = $adj_list[$i];
                for ($j = $i + 1; $j < count($adj_list); $j++) {
                    $n2 = $adj_list[$j];
                    if (isset($graph[$n1][$n2])) {
                        $group = [$node, $n1, $n2];
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
