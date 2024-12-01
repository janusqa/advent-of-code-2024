<?php

namespace Janusqa\Adventofcode\Day01;

class Day01B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $left = [];
        $right = [];
        $frequency = [];
        $similarity_score = 0;

        foreach ($lines as $index => $line) {
            $parts = preg_split('/\s+/', trim($line));
            $left[] = (int)$parts[0];
            $right[] = (int)$parts[1];

            if (isset($frequency[$right[$index]])) {
                $frequency[$right[$index]]++;
            } else {
                $frequency[$right[$index]] = 1;
            }
        }

        foreach ($left as $id) {
            $similarity_score += $id * ($frequency[$id] ?? 0);
        }

        // var_dump($frequency);
        // var_dump($right);

        echo ($similarity_score . PHP_EOL);
    }
}
