<?php

namespace Janusqa\Adventofcode\Day11;

class Day11B
{

    public function run(string $input): void
    {
        // $lines = explode("\n", trim($input));

        $stones = array_map(fn($n) => (int)$n, explode(" ", $input));

        $current = [];
        foreach ($stones as $stone) {
            $current[$stone] = ($current[$stone] ?? 0) + 1;
        }

        for ($i = 0; $i < 75; $i++) {
            $current = $this->blink($current);
        }

        echo array_sum($current) . PHP_EOL;
    }

    private function blink(array $current): array
    {
        $next = [];

        foreach ($current as $stone => $count) {
            if ($stone === 0) {
                $next[1] = ($next[1] ?? 0) + $count;
            } else {
                $num_digits = $stone == 0 ? 1 : floor(log10(abs($stone)) + 1);
                if ($num_digits % 2 === 0) {
                    $num_string = (string)$stone;
                    $left = (int)substr($num_string, 0, $num_digits / 2);
                    $right = (int)substr($num_string, $num_digits / 2);
                    $next[$left] = ($next[$left] ?? 0) + $count;
                    $next[$right] = ($next[$right] ?? 0) + $count;
                } else {
                    $next_stone = $stone * 2024;
                    $next[$next_stone] = ($next[$next_stone] ?? 0) + $count;
                }
            }
        }

        return $next;
    }
}
