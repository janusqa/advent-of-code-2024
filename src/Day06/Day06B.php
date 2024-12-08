<?php

namespace Janusqa\Adventofcode\Day06;

class Day06B
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $guard = ['^', '>', 'V', '<'];
        $guard_position = [];
        $probable_obstacle_location = 0;
        $free_space = '.';

        $grid = [];
        foreach ($lines as $row => $line) {
            $grid[] = str_split($line);
            foreach ($grid[$row] as $col => $char) {
                $guard_index = array_search($char, $guard, true);
                if ($guard_index !== false) {
                    $guard_position = [
                        'row' => $row,
                        'col' => $col,
                        'guard_index' => (int)$guard_index
                    ];
                }
            }
        }

        $path_traveled = array_values(array_unique(
            array_map(
                fn($position) => $position[0] . ',' . $position[1],
                $this->plot_path($grid, $guard, $guard_position, $free_space)
            )
        ));

        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        foreach ($path_traveled as $step) {
            $obstacle = array_map(fn($n) => (int)$n, explode(",", $step));

            if (!($obstacle[0] === $guard_position['row'] && $obstacle[1] === $guard_position['col'])) {
                $check_path = array_unique(
                    array_map(
                        fn($p) => $p[0] . ',' . $p[1],
                        $this->plot_path($grid, $guard, $guard_position, $free_space, $obstacle)
                    )
                );

                $last_move = array_map(fn($n) => (int)$n, explode(",", end($check_path)));

                if (!$this->OutOfBounds($last_move[0], $last_move[1], $RUBound, $CUBound)) {
                    $probable_obstacle_location++;
                }
            }
        }

        echo $probable_obstacle_location . PHP_EOL;
    }

    private function plot_path(array $grid, array $guard, array $guard_position, string $free_space, array $obstacle = []): array
    {
        $directions = ['^' => [-1, 0], '>' => [0, 1], 'V' => [1, 0], '<' => [0, -1]];

        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        $obstacle_seen = [];
        if (count($obstacle) > 0) {
            $grid[$obstacle[0]][$obstacle[1]] = '#';
        }

        $path_traveled[] = [$guard_position['row'], $guard_position['col']];

        while (true) {
            $new_r = $guard_position['row'] + $directions[$guard[$guard_position['guard_index']]][0];
            $new_c = $guard_position['col'] + $directions[$guard[$guard_position['guard_index']]][1];

            if ($this->OutOfBounds($new_r, $new_c, $RUBound, $CUBound)) {
                $grid[$guard_position['row']][$guard_position['col']] = $free_space;
                if (count($obstacle) > 0) {
                    $guard_position['row'] = $new_r;
                    $guard_position['col'] = $new_c;
                    $path_traveled[] = [$new_r, $new_c];
                }
                break;
            } elseif ($grid[$new_r][$new_c] === $free_space) {
                $grid[$guard_position['row']][$guard_position['col']] = $free_space;
                $guard_position['row'] = $new_r;
                $guard_position['col'] = $new_c;
                $grid[$new_r][$new_c] = $guard[$guard_position['guard_index']];
                $path_traveled[] = [$new_r, $new_c];
            } else {
                if (count($obstacle) > 0) {
                    $key = implode(',', [$new_r, $new_c]) . ',' . $guard_position['guard_index'];
                    $obstacle_seen[$key] = ($obstacle_seen[$key] ?? 0) + 1;
                    if ($obstacle_seen[$key] > 1) {
                        break;
                    }
                }

                $guard_position['guard_index'] = $this->Wrap($guard_position['guard_index'] + 1, count($guard));
            }
        }

        return $path_traveled;
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
