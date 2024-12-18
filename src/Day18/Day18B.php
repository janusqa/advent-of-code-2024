<?php

namespace Janusqa\Adventofcode\Day18;

class Day18B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $bytes = [];

        foreach ($lines as $line) {
            $bytes[] = array_map(fn($n) => (int)$n, explode(",", $line));
        }

        $RUBOUND = 70;
        $CUBOUND = 70;

        $start = [0, 0];
        $end = [$RUBOUND, $CUBOUND];

        $grid = [];

        $blockage = $this->Binarysearch($grid, $RUBOUND, $CUBOUND, $bytes, $start, $end);

        echo implode(",", $blockage) . PHP_EOL;
    }

    // BFS 
    private function bfs(array $grid, int $RUBOUND, int $CUBOUND, array $start, array $end): int
    {

        $queue = new \SplQueue();
        $directions = ['^' => [-1, 0], '>' => [0, 1], 'V' => [1, 0], '<' => [0, -1]];
        $visited = [];
        $best = PHP_INT_MAX;

        $queue->enqueue(['p' => $start, 'd' => 0, 'dir' => null]);

        while (!$queue->isEmpty()) {

            $step = $queue->dequeue();

            [$r, $c] = $step['p'];
            $d = $step['d'];

            $vkey = "$r,$c";

            if (isset($visited[$vkey])) continue;

            $visited[$vkey] = true;

            if ($r === $end[0] && $c === $end[1]) {
                $best = min($d, $best);
            }

            foreach ($directions as $next_dir => $direction) {
                $next_r = $r + $direction[0];
                $next_c = $c + $direction[1];

                if ($this->OutOfBounds($next_r, $next_c, $RUBOUND, $CUBOUND)) continue;
                if (($grid[$next_r][$next_c] ?? '.') === "#") continue;

                $queue->enqueue(['p' => [$next_r, $next_c], 'd' => $d + 1, 'dir' => $next_dir]);
            }
        }

        return $best === PHP_INT_MAX ? -1 : $best;
    }

    # Binary Search
    private function Binarysearch(array $grid, int $RUBOUND, int $CUBOUND, array $bytes, array $start, array $end): array
    {
        $low = 1024; // Confirmad from Part 1 that 0-1024 bytes created no blockages.
        $high = count($bytes) - 1;

        // Binary search to find the point where the path is blocked
        while ($low <= $high) {
            $mid = (int)(($low + $high) / 2);

            // Place walls up to $mid index
            $this->SeedGrid($grid, $bytes, $mid);

            // Check if the path is blocked
            $steps = $this->bfs($grid, $RUBOUND, $CUBOUND, $start, $end);

            if ($steps === -1) { // Path is blocked
                $high = $mid - 1;   // Search in the left half
            } else {
                $low = $mid + 1; // Search in the right half
            }
        }

        if ($steps !== -1) {
            // Search right for the first block. We should be close.
            while ($mid < count($bytes) && $this->bfs($grid, $RUBOUND, $CUBOUND, $start, $end) !== -1) {
                $mid++;
                $this->SeedGrid($grid, $bytes, $mid);
            }
        } else {
            // Search left for the first block. We should be close.
            while ($mid > 0 && $this->bfs($grid, $RUBOUND, $CUBOUND, $start, $end) === -1) {
                $mid--;
                $this->SeedGrid($grid, $bytes, $mid);
            }
        }

        return $bytes[$mid];
    }

    private function SeedGrid(array &$grid, array $bytes, int $to): void
    {
        $grid = [];
        for ($i = 0; $i <= $to; $i++) {
            [$x, $y] = $bytes[$i];
            $grid[$y][$x] = '#';
        }
    }

    private function OutOfBounds(int $row, int $col, int $RUBound, int $CUBound): bool
    {
        return (0 > $row || $row > $RUBound || 0 > $col || $col > $CUBound);
    }

    private function printGrid(array $grid, int $rows, int $cols, string $file = ""): void
    {
        $output = "";

        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                $output .= isset($grid[$r][$c]) ? $grid[$r][$c] : '.';
            }
            $output .= PHP_EOL;
        }

        $output .= PHP_EOL;

        if (!empty($file)) {
            file_put_contents($file, $output, FILE_APPEND);
        } else {
            echo $output;
        }
    }
}
