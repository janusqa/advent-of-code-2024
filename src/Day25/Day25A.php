<?php

namespace Janusqa\Adventofcode\Day25;

class Day25A
{
    public function run(string $input): void
    {
        $schematics = explode("\n\n", trim($input));

        $s = explode("\n", $schematics[0]);
        $HEIGHT = count($s);

        $keys = [];
        $locks = [];

        foreach ($schematics as $schematic) {
            $is_lock = strpos($schematic, "#") === 0;
            $pin = [];
            foreach (explode("\n", $schematic) as $row) {
                foreach (str_split($row) as $idx => $col) {
                    if ($col === "#") {
                        $pin[$idx] = ($pin[$idx] ?? 0) + 1;
                    } else {
                        $pin[$idx] = ($pin[$idx] ?? 0);
                    }
                }
            }
            if ($is_lock) {
                $locks[] = $pin;
            } else {
                $keys[] = $pin;
            }
        }

        $pairs = 0;
        foreach ($locks as $lock) {
            foreach ($keys as $key) {
                print_r(implode(",", $lock) . PHP_EOL);
                print_r(implode(",", $key) . PHP_EOL . PHP_EOL);
                $is_match = true;
                foreach ($key as $idx => $pin) {
                    if ($pin + $lock[$idx] > $HEIGHT) {
                        $is_match = false;
                        break;
                    }
                }
                if ($is_match) $pairs++;
            }
        }


        echo $pairs . PHP_EOL;
    }
}
