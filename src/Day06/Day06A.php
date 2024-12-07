<?php

namespace Janusqa\Adventofcode\Day06;

class Day06A
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $guard = ['^', '>', 'V', '<'];
        $directions = ['^' => [-1, 0], '>' => [0, 1], 'V' => [1, 0], '<' => [0, -1]];
        $guard_position = [];
        $free_space = '.';
        $path_traveled = [];

        $grid = [];
        foreach ($lines as $row => $line) {
            $grid[] = str_split($line);
            foreach ($grid[$row] as $col => $char) {
                $guard_index = array_search($char, $guard, true);
                if ($guard_index !== false) {
                    $guard_position = [
                        'row' => $row,
                        'col' => $col,
                        'guard_index' => $guard_index
                    ];
                }
            }
        }

        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        $path_traveled[] = [$guard_position['row'], $guard_position['col']];

        while (true) {
            $new_r = $guard_position['row'] + $directions[$guard[$guard_position['guard_index']]][0];
            $new_c = $guard_position['col'] + $directions[$guard[$guard_position['guard_index']]][1];

            if ($this->OutOfBounds($new_r, $new_c, $RUBound, $CUBound)) {
                $grid['row']['col'] = $free_space;
                break;
            }

            if ($grid[$new_r][$new_c] === $free_space) {
                $grid[$guard_position['row']][$guard_position['col']] = $free_space;
                $guard_position['row'] = $new_r;
                $guard_position['col'] = $new_c;
                $grid[$new_r][$new_c] = $guard[$guard_position['guard_index']];
                $path_traveled[] = [$new_r, $new_c];
            } else {
                $guard_position['guard_index'] = $this->Wrap($guard_position['guard_index'] + 1, count($guard));
            }
        }

        echo (count(array_unique(array_map(fn($position) => $position[0] . ',' . $position[1], $path_traveled))) . PHP_EOL);
    }

    private function OutOfBounds(int $row, int $col, int $URBound, int $CUBound): bool
    {
        return (0 > $row || $row > $URBound || 0 > $col || $col > $CUBound);
    }

    private function Wrap(int $index, int $wrapAt): int
    {
        return ($index % $wrapAt + $wrapAt) % $wrapAt;
    }
}
