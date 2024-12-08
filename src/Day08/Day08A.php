<?php

namespace Janusqa\Adventofcode\Day08;

class Day08A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));




        echo "" . PHP_EOL;
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
