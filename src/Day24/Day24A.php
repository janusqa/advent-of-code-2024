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
            $gates[$matches[4]] = [$matches[1], $matches[2], $matches[3], $matches[4]];
            $wires[$matches[1]] = $wires[$matches[1]] ?? null;
            $wires[$matches[3]] = $wires[$matches[3]] ?? null;
            $wires[$matches[4]] = $wires[$matches[4]] ?? null;
        }

        $wires_z = $this->getWires('z', $wires);

        $result_bin = implode('', array_map(fn($n) => $this->adder($n, $gates, $wires), array_keys($wires_z)));
        $result_dec = bindec($result_bin);

        echo $result_dec . " ($result_bin)" . PHP_EOL;
    }

    private function adder(string $wire, array &$gates, array &$wires): int
    {
        if (isset($wires[$wire])) return $wires[$wire];

        [$a, $op, $b] = $gates[$wire];

        $sa = $this->adder($a, $gates, $wires);
        $sb = $this->adder($b, $gates, $wires);

        $wires[$wire] = $this->gate($sa, $sb, $op);

        return $wires[$wire];
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

    private function getWires(string $wire, array $wires)
    {
        $w = array_filter($wires, fn($n, $k) => strpos($k, $wire) === 0, ARRAY_FILTER_USE_BOTH);

        uksort($w, function ($a, $b) {
            return (int) substr($b, 1) <=> (int) substr($a, 1); // Sort descending by numeric suffix
        });

        return $w;
    }
}
