<?php

namespace Janusqa\Adventofcode\Day04;

class Day04B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $directions = [
            "nw" => [-1, -1],
            "ne" => [-1, 1],
            "se" => [1, 1],
            "sw" => [1, -1],
        ];

        $word = "MAS";
        $word_count = 0;

        $grid = [];

        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }

        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        foreach ($grid as $row_index => $row) {
            foreach ($row as $col_index => $col) {
                if ($col === "A") {
                    $nwr = $row_index + $directions["nw"][0];
                    $nwc = $col_index + $directions["nw"][1];
                    $ser = $row_index + $directions["se"][0];
                    $sec = $col_index + $directions["se"][1];
                    $ner = $row_index + $directions["ne"][0];
                    $nec = $col_index + $directions["ne"][1];
                    $swr = $row_index + $directions["sw"][0];
                    $swc = $col_index + $directions["sw"][1];
                    if (
                        !$this->OutOfBounds($nwr, $nwc, $RUBound, $CUBound) &&
                        !$this->OutOfBounds($ser, $sec, $RUBound, $CUBound) &&
                        !$this->OutOfBounds($ner, $nec, $RUBound, $CUBound) &&
                        !$this->OutOfBounds($swr, $swc, $RUBound, $CUBound)
                    ) {
                        $x1 = implode('', [$grid[$nwr][$nwc], $col, $grid[$ser][$sec]]);
                        $x2 = implode('', [$grid[$ner][$nec], $col, $grid[$swr][$swc]]);

                        if (($x1 === $word || strrev($x1) === $word) && ($x2 === $word || strrev($x2) === $word)) {
                            $word_count++;
                        }
                    }
                }
            }
        }

        echo ($word_count . PHP_EOL);
    }

    private function OutOfBounds(int $row, int $col, int $URBound, int $CUBound): bool
    {
        return (0 > $row || $row > $URBound || 0 > $col || $col > $CUBound);
    }
}
