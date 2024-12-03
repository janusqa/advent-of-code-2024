<?php

namespace Janusqa\Adventofcode\Day03;

class Day03B
{

    public function run(string $input): void
    {
        // $lines = explode("\n", trim($input));
        $pattern = '/(?:(don\'t\(\)_)?)mul\((\d{1,3}),(\d{1,3})\)/';
        $result = 0;

        if (preg_match_all($pattern, $input, $matches)) {
            foreach ($matches[2] as $index => $match) {
                if (empty($matches[1][$index])) {
                    $result += intval($match) * intval($matches[3][$index]);
                }
            }
            // print_r($matches);
        }

        echo ($result . PHP_EOL);
    }
}
