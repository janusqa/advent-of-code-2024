<?php

namespace Janusqa\Adventofcode\Day03;

class Day03A
{

    public function run(string $input): void
    {
        // $lines = explode("\n", trim($input));
        $pattern = '/mul\((\d{1,3}),(\d{1,3})\)/';
        $result = 0;

        if (preg_match_all($pattern, $input, $matches)) {
            foreach ($matches[1] as $index => $match) {
                $result += intval($match) * intval($matches[2][$index]);
            }
        }

        echo ($result . PHP_EOL);
    }
}
