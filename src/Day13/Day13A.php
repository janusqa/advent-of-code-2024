<?php

namespace Janusqa\Adventofcode\Day13;

class Day13A
{

    public function run(string $input): void
    {

        $lines = explode("\n\n", trim($input));

        $pattern_button = '/Button (A|B): X\+(\d{1,}), Y\+(\d{1,})/';
        $pattern_prize = '/(Prize): X=(\d{1,}), Y=(\d{1,})/';

        $games = [];

        foreach ($lines as $block) {
            [$button_a, $button_b, $prize] = explode("\n", $block);
            preg_match($pattern_button, $button_a, $a);
            preg_match($pattern_button, $button_b, $b);
            preg_match($pattern_prize, $prize, $p);
            $games[] = [$a[1] => [(int)$a[2], (int)$a[3]], $b[1] => [(int)$b[2], (int)$b[3]], $p[1] => [(int)$p[2], (int)$p[3]]];
        }

        $total_tokens = 0;

        foreach ($games as $game) {
            [$a_times, $b_times] = $this->play($game);
            $total_tokens += ($a_times * 3) + $b_times;
        }

        echo $total_tokens . PHP_EOL;
    }

    private function play(array &$game)
    {
        for ($x = 1; $x < 101; $x++) {
            for ($y = 1; $y < 101; $y++) {
                $prize_x = $x * $game['A'][0] + $y * $game['B'][0];
                $prize_y = $x * $game['A'][1] + $y * $game['B'][1];

                if ($prize_x === $game['Prize'][0] && $prize_y === $game['Prize'][1]) {
                    return [$x, $y];
                }
            }
        }

        return [0, 0];
    }
}
