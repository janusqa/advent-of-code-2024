<?php

namespace Janusqa\Adventofcode\Day01;

class Day01A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $left = [];
        $right = [];
        $total_distance = 0;

        foreach ($lines as $index => $line) {
            $parts = preg_split('/\s+/', trim($line));
            $left[] = (int)$parts[0];
            $right[] = (int)$parts[1];
        }

        sort($left);
        sort($right);

        foreach ($left as $index => $id) {
            $total_distance += abs($id - $right[$index]);
        }

        // var_dump($left);
        // var_dump($right);

        echo ($total_distance . PHP_EOL);
    }
}
