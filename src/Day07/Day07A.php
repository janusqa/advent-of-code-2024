<?php

namespace Janusqa\Adventofcode\Day07;

class Day07A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $total_calibration = 0;

        foreach ($lines as $line) {
            [$total, $operands] = explode(": ", $line);
            $total = (int)$total;
            $operands = array_map(fn($n) => (int)$n, explode(" ", $operands));

            $size = count($operands) - 1;

            foreach ($this->getOperatorSequence(['*', '+'], $size, '') as $operators) {
                $running_total = 0;
                $a = 0;
                $b = 1;

                foreach ($operators as $index => $operator) {
                    $running_total = $this->operation($operator, $index > 0 ? $running_total : $operands[$a], $operands[$b]);
                    if ($running_total > $total) {
                        break;
                    }
                    $b++;
                }

                if ($running_total === $total) {
                    $total_calibration += $total;
                    break;
                }
            }
        }

        echo $total_calibration . PHP_EOL;
    }

    private function getOperatorSequence(array $set, int $size, string $current): \Generator
    {
        if ($size === 0) {
            // Base case: yield the current sequence as an array
            yield str_split($current);
            return;
        }

        // Append each element and recurse
        foreach ($set as $element) {
            yield from $this->getOperatorSequence($set, $size - 1, $current . $element);
        }
    }

    private function operation(string $operator, int $a, int $b): int|null
    {
        switch ($operator) {
            case '+':
                return $a + $b;
                break;

            case '*':
                return $a * $b;
                break;

            default:
                return null;
                break;
        }
    }
}
