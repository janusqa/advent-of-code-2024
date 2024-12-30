<?php

namespace Janusqa\Adventofcode\Day24;

class Day24B
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

        $gates_output = [];
        $gates_intermediary = [];
        $wires_z = $this->getWires('z', $wires);

        foreach ($gates as $idx => $gate) {
            $x = substr($gate[0], 0, 1);
            $y = substr($gate[2], 0, 1);
            $z = substr($gate[3], 0, 1);

            if ($z === 'z' && $gate[1] !== 'XOR' && $gate[3] !== array_key_first($wires_z)) {
                $gates_output[$idx] = $gate;
            }

            if (
                $z !== 'z' &&
                $x !== 'x' &&
                $y !== 'x' &&
                $x !== 'y' &&
                $y !== 'y' &&
                $gate[1] === 'XOR'
            ) {
                $gates_intermediary[$idx] = $gate;
            }
        }

        foreach ($gates_intermediary as $igate) {
            $queue = new \SplQueue();
            $queue->enqueue($igate);
            $seen = [];
            while (!$queue->isEmpty()) {
                [$cx, $cop, $cy, $cz] = $queue->dequeue();

                $k = implode(',', [$cx, $cop, $cy, $cz]);
                if (isset($seen[$k])) continue;

                $seen[$k] = true;

                if (strpos($cz, 'z') === 0) {
                    $swap = "z" . str_pad(((int)substr($cz, 1)) - 1, 2, '0', STR_PAD_LEFT);

                    $gates[$swap] = [...$igate];
                    $gates[$swap][3] = $swap;

                    $gates[$igate[3]] = [...$gates_output[$swap]];
                    $gates[$igate[3]][3] = $igate[3];

                    break;
                }

                foreach ($gates as $gate) {
                    if ($cz === $gate[0] || $cz === $gate[2]) $queue->enqueue($gate);
                }
            }
        }

        $wires_x = $this->getWires('x', $wires);
        $wires_y = $this->getWires('y', $wires);
        $wires_z = $this->getWires('z', $wires);

        $bad_sum = implode('', array_map(fn($n) => $this->adder($n, $gates, $wires), array_keys($wires_z)));
        $error_dec = ((bindec(implode('', $wires_x)) + bindec(implode('', $wires_y))) ^ bindec($bad_sum));
        $error_bin = decbin($error_dec);
        $trailing_zeros = strval(strspn(strrev($error_bin), '0'));

        $swaps = [...array_keys($gates_intermediary), ...array_keys($gates_output)];
        foreach ($gates as $gate) {
            if (substr($gate[0], -2) === $trailing_zeros && substr($gate[2], -2) === $trailing_zeros) {
                $swaps[] = $gate[3];
            }
        }

        sort($swaps);

        echo implode(',', $swaps) . PHP_EOL;
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

    private function getWires(string $wire, array $wires): array
    {
        $w = array_filter($wires, fn($n, $k) => strpos($k, $wire) === 0, ARRAY_FILTER_USE_BOTH);

        uksort($w, function ($a, $b) {
            return (int) substr($b, 1) <=> (int) substr($a, 1); // Sort descending by numeric suffix
        });

        return $w;
    }
}
