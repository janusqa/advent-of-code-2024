<?php

namespace Janusqa\Adventofcode\Day11;

class Day11A
{

    public function run(string $input): void
    {
        // $lines = explode("\n", trim($input));

        $stones = array_map(fn($n) => (int)$n, explode(" ", $input));

        for ($i = 0; $i < 50; $i++) {
            $stones = $this->blink($stones);
        }

        echo count($stones) . PHP_EOL;
    }

    private function blink(array $stones): array
    {

        $new_stones = [];
        foreach ($stones as $stone) {
            if ($stone === 0) {
                $new_stones[] = 1;
            } else {
                $num_digits = $stone == 0 ? 1 : floor(log10(abs($stone)) + 1);
                if ($num_digits % 2 === 0) {
                    $num_string = (string)$stone;
                    $left = (int)substr($num_string, 0, $num_digits / 2);
                    $right = (int)substr($num_string, $num_digits / 2);
                    $new_stones[] = $left;
                    $new_stones[] = $right;
                } else {
                    $new_stones[] = $stone * 2024;
                }
            }
        }

        return $new_stones;
    }
}
