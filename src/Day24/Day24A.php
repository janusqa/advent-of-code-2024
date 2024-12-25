<?php

namespace Janusqa\Adventofcode\Day24;

class Day24A
{
    public function run(string $input): void
    {
        [$w, $g] = explode("\n\n", trim($input));

        $pattern_initial_wires = '/((?:x|y)\d{1,}): (1|0)/';
        $pattern_gates = '/(.{3}) (AND|OR|XOR) (.{3}) -> (.{3})/';

        $wires = [];
        foreach (explode("\n", $w) as $wire) {
            preg_match($pattern_initial_wires, $wire, $matches);
            $wires[$matches[1]] = (int)$matches[2];
        }

        $gates = [];
        foreach (explode("\n", $g) as $gate) {
            preg_match($pattern_gates, $gate, $matches);
            $gates[] = [$matches[1], $matches[2], $matches[3], $matches[4]];
        }

        while (count($gates) > 0) {
            $gates_copy  = $gates;
            foreach ($gates as $idx => $gate) {
                if (isset($wires[$gate[0]]) && isset($wires[$gate[2]])) {
                    $wires[$gate[3]] = $this->gate($wires[$gate[0]], $wires[$gate[2]], $gate[1]);
                    $gates_copy = [...array_slice($gates, 0, $idx), ...array_slice($gates, $idx + 1)];
                }
            }
            $gates = $gates_copy;
        }

        ksort($wires);
        $digits_bin = array_filter($wires, function ($key) {
            return strpos($key, 'z') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $decimal = bindec(implode('', array_reverse($digits_bin)));

        echo $decimal . PHP_EOL;
    }

    private function gate(int $a, int $b, string $operator): int
    {
        $operator = strtoupper($operator);

        if ($operator === 'AND') {
            return $a & $b;
        } elseif ($operator === 'OR') {
            return $a | $b;
        } elseif ($operator === 'XOR') {
            return $a ^ $b;
        } else {
            throw new \InvalidArgumentException("Invalid operator: $operator.");
        }
    }
}
