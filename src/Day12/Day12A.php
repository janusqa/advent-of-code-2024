<?php

namespace Janusqa\Adventofcode\Day12;

class Day12A
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
                [$area, $perimeter] = $this->survey($grid, $directions, $RUBOUND, $CUBOUND, $visited, $ridx, $cidx, $plant);
                $price += $area * $perimeter;
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
        $perimeter = 0;

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
                    $perimeter++;
                    continue;
                }

                if (isset($visited["$new_ridx,$new_cidx"])) {
                    continue;
                }

                $queue->enqueue([$new_ridx, $new_cidx]);
            }
        }

        return [$area, $perimeter];
    }

    private function OutOfBounds(int $row, int $col, int $RUBOUND, int $CUBOUND): bool
    {
        return (0 > $row || $row > $RUBOUND || 0 > $col || $col > $CUBOUND);
    }
}
