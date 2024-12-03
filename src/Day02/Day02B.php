<?php

namespace Janusqa\Adventofcode\Day02;

class Day02B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));
        $safe = 0;

        foreach ($lines as $index => $line) {
            $levels = array_map('intval', preg_split('/\s+/', trim($line)));
            if ($this->isSafe($levels)) {
                $safe++;
            } else {
                foreach ($levels as $index => $level) {
                    $dampened_report = array_merge(array_slice($levels, 0, $index), array_slice($levels, $index + 1));
                    if ($this->isSafe($dampened_report)) {
                        $safe++;
                        break;
                    }
                }
            }
        }

        echo ($safe . PHP_EOL);
    }

    private function isSafe(array $levels): bool
    {
        $shouldIncrease = ($levels[1] - $levels[0]) < 0;
        $shouldDecrease = ($levels[1] - $levels[0]) > 0;

        for ($i = 1; $i < count($levels); $i++) {
            $differential = $levels[$i] - $levels[$i - 1];
            $isIncreasing = $differential < 0;
            $isDecreasing = $differential > 0;
            if (($differential == 0 || abs($differential) > 3) || ($shouldIncrease && !$isIncreasing) || ($shouldDecrease && !$isDecreasing)) {
                return false;
            }
        }
        return true;
    }
}
