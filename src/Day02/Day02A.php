<?php

namespace Janusqa\Adventofcode\Day02;

class Day02A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));
        $safe = count($lines);

        foreach ($lines as $index => $line) {
            $levels = array_map('intval', preg_split('/\s+/', trim($line)));
            $shouldIncrease = ($levels[1] - $levels[0]) < 0;
            $shouldDecrease = ($levels[1] - $levels[0]) > 0;

            for ($i = 1; $i < count($levels); $i++) {
                $differential = $levels[$i] - $levels[$i - 1];
                $isIncreasing = $differential < 0;
                $isDecreasing = $differential > 0;
                if (($differential == 0 || abs($differential) > 3) || ($shouldIncrease && !$isIncreasing) || ($shouldDecrease && !$isDecreasing)) {
                    $safe--;
                    break;
                }
            }
        }

        echo ($safe . PHP_EOL);
    }
}
