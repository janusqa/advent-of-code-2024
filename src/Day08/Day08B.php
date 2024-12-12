<?php

namespace Janusqa\Adventofcode\Day08;

class Day08B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $grid = [];
        $antenne = [];

        foreach ($lines as $row => $line) {
            $grid[] = str_split($line);
            foreach ($grid[$row] as $col => $grid_position) {
                if ($grid_position !== '.') {
                    $antenne[$grid_position][] = $row . ',' . $col;
                }
            }
        }

        $RUBound = count($grid) - 1;
        $CUBound = count($grid[0]) - 1;

        $antinodes = [];

        foreach ($antenne as $group) {
            foreach ($this->dfs($group, 2, 0, []) as $pair) {
                $antinodes = array_merge($antinodes, $this->getAntinodes($pair, $RUBound, $CUBound));
            }
        }

        echo count(array_unique($antinodes)) . PHP_EOL;
    }

    private function getAntinodes(array $pair, int $RUBound, int $CUBound): array
    {

        $antinodes = [];

        $a = explode(",", $pair[0]);
        $b = explode(",", $pair[1]);

        $dar = $a[0] - $b[0];
        $dac = $a[1] - $b[1];
        $dbr = $b[0] - $a[0];
        $dbc = $b[1] - $a[1];

        $node = $a;
        $harmonic = 1;

        while (!$this->OutOfBounds($node[0], $node[1], $RUBound, $CUBound)) {
            $antinodes[] = implode(",", $node);
            $node = [($dar * $harmonic) + $a[0], ($dac * $harmonic) + $a[1]];
            $harmonic++;
        }

        $node = $b;
        $harmonic = 1;

        while (!$this->OutOfBounds($node[0], $node[1], $RUBound, $CUBound)) {
            $antinodes[] = implode(",", $node);
            $node = [($dbr * $harmonic) + $b[0], ($dbc * $harmonic) + $b[1]];
            $harmonic++;
        }

        return $antinodes;
    }

    // private function dfs(array $items, int $size, int $start, array $current, array &$result): void
    private function dfs(array $items, int $size, int $start, array $current): \Generator
    {
        if (count($current) === $size) {
            // $result[] = $current; 
            yield $current;
            return;
        }

        for ($i = $start; $i < count($items); $i++) {
            $current[] = $items[$i];
            // yield from $this->dfs($items, $size, $i + 1, $current, $result);
            yield from $this->dfs($items, $size, $i + 1, $current);
            array_pop($current); // Backtrack
        }
    }

    private function OutOfBounds(int $row, int $col, int $RUBound, int $CUBound): bool
    {
        return (0 > $row || $row > $RUBound || 0 > $col || $col > $CUBound);
    }
}