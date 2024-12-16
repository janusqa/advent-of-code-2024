<?php

namespace Janusqa\Adventofcode\Day12;

class Day12B
{

    public function run(string $input): void
    {

        $lines = explode("\n", trim($input));

        $RUBOUND = count($lines) - 1;
        $CUBOUND = strlen($lines[0]) - 1;

        $directions = [[-1, 0], [0, 1], [1, 0], [0, -1]];

        $grid = [];

        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }

        $visited = [];

        $price = 0;

        foreach ($grid as $ridx => $row) {
            foreach ($row as $cidx => $plant) {
                [$area, $sides] = $this->survey($grid, $directions, $RUBOUND, $CUBOUND, $visited, $ridx, $cidx, $plant);
                $price += $area * $sides;
                // if ($area > 0) {
                //     print_r("A region of $plant plants with price $area * $sides = " . $area * $sides . '.' . PHP_EOL);
                // }
            }
        }

        echo $price . PHP_EOL;
    }

    // BFS
    private function survey(array $grid, array $directions, int $RUBOUND, int $CUBOUND, array &$visited, int $ridx, int $cidx, string $plant): array
    {

        if (isset($visited["$ridx,$cidx"])) {
            return [0, 0];
        }

        $queue = new \SplQueue();

        $area = 0;
        $perimeters = [];

        $queue->enqueue([$ridx, $cidx]);

        while (!$queue->isEmpty()) {

            [$current_ridx, $current_cidx] = $queue->dequeue();

            if ($grid[$current_ridx][$current_cidx] !== $plant || isset($visited["$current_ridx,$current_cidx"])) {
                continue;
            }

            $visited["$current_ridx,$current_cidx"] = true;
            $area++;

            foreach ($directions as $direction) {
                $new_ridx = $current_ridx + $direction[0];
                $new_cidx = $current_cidx + $direction[1];

                if ($this->OutOfBounds($new_ridx, $new_cidx, $RUBOUND, $CUBOUND) || $grid[$new_ridx][$new_cidx] !== $plant) {
                    $perimeters[] = "$current_ridx,$current_cidx,$new_ridx,$new_cidx";
                    continue;
                }

                if (isset($visited["$new_ridx,$new_cidx"])) {
                    continue;
                }

                $queue->enqueue([$new_ridx, $new_cidx]);
            }
        }

        return [$area, $this->sides($directions, $perimeters)];
    }

    private function sides(array $directions, array $perimeters): int
    {
        $visited = [];
        $sides = 0;

        foreach ($perimeters as $perimeter) {
            if (isset($visited[$perimeter])) {
                continue;
            }

            $sides++;

            [$p0, $p1, $p2, $p3] = explode(",", $perimeter);

            foreach ($directions as $direction) {
                $new_p0 = $p0 + $direction[0];
                $new_p1 = $p1 + $direction[1];
                $new_p2 = $p2 + $direction[0];
                $new_p3 = $p3 + $direction[1];

                while (in_array("$new_p0,$new_p1,$new_p2,$new_p3", $perimeters)) {
                    $visited["$new_p0,$new_p1,$new_p2,$new_p3"] = true;
                    $new_p0 += $direction[0];
                    $new_p1 += $direction[1];
                    $new_p2 += $direction[0];
                    $new_p3 += $direction[1];
                }
            }
        }

        return $sides;
    }

    private function OutOfBounds(int $row, int $col, int $RUBOUND, int $CUBOUND): bool
    {
        return (0 > $row || $row > $RUBOUND || 0 > $col || $col > $CUBOUND);
    }
}
