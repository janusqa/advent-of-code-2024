<?php

namespace Janusqa\Adventofcode\Day09;

class Day09B
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


        for ($i = count($files) - 1; $i >= 0; $i--) {
            for ($j = 0; $j < count($spaces); $j++) {
                if ($spaces[$j][0] > $files[$i][0][0]) {
                    break;
                }
                if ($spaces[$j][1] >= $files[$i][0][1]) {
                    $old_file_start = $files[$i][0][0];
                    $files[$i][0][0] = $spaces[$j][0];
                    $spaces[$j][0] = $files[$i][0][0] + $files[$i][0][1];
                    $spaces[$j][1] = $spaces[$j][1] - $files[$i][0][1];
                    $spaces[] = [$old_file_start, $files[$i][0][1]];
                    break;
                }
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

        $checksum = 0;

        foreach ($blocks as $pos => $block) {
            if (isset($block)) {
                $checksum += $pos * $block;
            }
        }

        echo $checksum . PHP_EOL;
    }
}
