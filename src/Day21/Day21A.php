<?php

namespace Janusqa\Adventofcode\Day21;

class Day21A
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $keypad_num_pos = [
            '7' => [0, 0],
            '8' => [0, 1],
            '9' => [0, 2],
            '4' => [1, 0],
            '5' => [1, 1],
            '6' => [1, 2],
            '1' => [2, 0],
            '2' => [2, 1],
            '3' => [2, 2],
            ' ' => [3, 0],
            '0' => [3, 1],
            'A' => [3, 2],
        ];

        $keypad_dir_pos = [
            ' ' => [0, 0],
            '^' => [0, 1],
            'A' => [0, 2],
            '<' => [1, 0],
            'v' => [1, 1],
            '>' => [1, 2],
        ];

        $keypad_num_map = $this->graph($keypad_num_pos, [3, 0]);
        $keypad_dir_map = $this->graph($keypad_dir_pos, [0, 0]);

        $result = 0;
        foreach ($lines as $line) {
            $keystrokes = $this->next($line, $keypad_num_map);
            $keystrokes = $this->next($keystrokes, $keypad_dir_map);
            $keystrokes = $this->next($keystrokes, $keypad_dir_map);
            $result += intval(substr($line, 0, 3)) * strlen($keystrokes);
        }

        echo $result . PHP_EOL;
    }

    private function graph(array $keypad, array $invalid_move): array
    {
        $graph = [];
        foreach ($keypad as $a => [$x1, $y1]) {
            foreach ($keypad as $b => [$x2, $y2]) {
                $path =
                    str_repeat('<', max(0, $y1 - $y2)) . // Move left if y1 > y2
                    str_repeat('v', max(0, $x2 - $x1)) . // Move down if x2 > x1
                    str_repeat('^', max(0, $x1 - $x2)) . // Move up if x1 > x2
                    str_repeat('>', max(0, $y2 - $y1));  // Move right if y2 > y1
                if ([$x1, $y2] == $invalid_move || [$x2, $y1] == $invalid_move) {
                    $path = strrev($path);
                }
                $graph["$a,$b"] = $path . 'A';
            }
        }
        return $graph;
    }

    private function next(string $keystrokes, array $graph): string
    {
        $next_keystrokes = '';
        $prev_keystroke = 'A';

        foreach (str_split($keystrokes) as $keystroke) {
            $next_keystrokes .= $graph["$prev_keystroke,$keystroke"];
            $prev_keystroke = $keystroke;
        }

        return $next_keystrokes;
    }
}
