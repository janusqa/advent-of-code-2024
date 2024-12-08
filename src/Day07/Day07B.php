<?php

namespace Janusqa\Adventofcode\Day07;

class Day07B
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $total_calibration = 0;

        foreach ($lines as $line) {
            [$value, $numbers] = explode(": ", $line);
            $target = (int)$value;
            $numbers = array_map('intval', explode(" ", $numbers));

            // Check if the target can be reached using the numbers
            if ($this->isValidEquation($numbers, $target)) {
                $total_calibration += $target;
            }
        }

        echo $total_calibration . PHP_EOL;
    }

    private function isValidEquation(array $numbers, int $target): bool
    {
        // Start exploring from the first number
        return $this->dfs($numbers, 1, $numbers[0], $target);
    }

    private function dfs(array $numbers, int $index, int $currentTotal, int $target): bool
    {
        // Base case: If we've used all numbers, check if the total matches the target
        if ($index === count($numbers)) {
            return $currentTotal === $target;
        }

        // Add the current number and proceed
        if ($this->dfs($numbers, $index + 1, $currentTotal + $numbers[$index], $target)) {
            return true;
        }

        // Multiply the current number and proceed
        if ($this->dfs($numbers, $index + 1, $currentTotal * $numbers[$index], $target)) {
            return true;
        }

        // If no valid path found, return false
        return false;
    }
}
