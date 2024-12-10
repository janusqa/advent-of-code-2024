<?php

namespace Janusqa\Adventofcode\Day09;

class Day09A
{

    public function run(string $input): void
    {
        // $lines = explode("\n", trim($input));

        $files = [];
        $spaces = [];

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            if ($i % 2 === 0) {
                // File
                [$start, $size] = !empty($spaces) ? array_map(fn($n) => (int)$n, end($spaces)) : [0, 0];
                $files[$i / 2][] = [$start + $size, (int)$input[$i]];
            } else {
                // Free Space
                $last_file = end($files);
                [$start, $size] = (!empty($files) && !empty($last_file)) ? array_map(fn($n) => (int)$n, end($last_file)) : [0, 0];
                $spaces[] = [$start + $size, (int)$input[$i]];
            }
        }

        $blocks = [];

        foreach ($files as $id => $file) {
            for ($i = $file[0][0]; $i < $file[0][0] + $file[0][1]; $i++) {
                $blocks[$i] = $id;
            }
        }

        foreach ($spaces as $space) {
            for ($i = $space[0]; $i < $space[0] + $space[1]; $i++) {
                $blocks[$i] = null;
            }
        }

        ksort($blocks);
        $p_start = 0;
        $p_end = count($blocks) - 1;

        while ($p_start < $p_end) {
            if (isset($blocks[$p_start])) {
                $p_start++;
            }

            if (!isset($blocks[$p_end])) {
                $p_end--;
            }

            if (!isset($blocks[$p_start]) && isset($blocks[$p_end])) {
                $blocks[$p_start] = $blocks[$p_end];
                $blocks[$p_end] = null;
            }
        }

        $checksum = 0;

        foreach ($blocks as $pos => $block) {
            $checksum += $pos * $block;
        }

        echo $checksum . PHP_EOL;
    }
}
