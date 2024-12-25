<?php

namespace Janusqa\Adventofcode\Day23;

class Day23A
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $connections = array_map(fn($n) => explode('-', $n), $lines);

        for ($i = 0; $i < count($connections) - 1; $i++) {
            for ($j = $i + 1; $j < count($connections); $j++) {
                if () {
                    
                }

                $d = $this->manhattan(explode(",", $steps[$i]), explode(",", $steps[$j]));

                if ($d <= 20 && $jpath - $ipath > $d) {
                    $cheats[$jpath - $ipath - $d] = ($cheats[$jpath - $ipath - $d] ?? 0) + 1;
                }
            }
        }

        echo "" . PHP_EOL;
    }
}
