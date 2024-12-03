<?php

namespace Janusqa\Adventofcode\Day03;

class Day03B
{

    public function run(string $input): void
    {
        // $lines = explode("\n", trim($input));
        $pattern_code = '/(don\'t\(\))|(do\(\))|mul\((\d{1,3}),(\d{1,3})\)/';
        $pattern_instruction = '/mul\((\d{1,3}),(\d{1,3})\)/';
        $result = 0;

        if (preg_match_all($pattern_code, $input, $matches)) {
            $disabled = false;
            foreach ($matches[0] as $match) {
                if ($match === "don't()") {
                    $disabled = true;
                } elseif ($match === "do()") {
                    $disabled = false;
                } else {
                    if (!$disabled && preg_match($pattern_instruction, $match, $instruction)) {
                        $result += (int)$instruction[1] * (int)$instruction[2];
                    }
                }
            }
        }

        echo ($result . PHP_EOL);
    }
}
