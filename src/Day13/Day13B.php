<?php

namespace Janusqa\Adventofcode\Day13;

class Day13B
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

    private function play(array $game, int $prize_increase = 10000000000000): array
    {
        /**
         * https://en.wikipedia.org/wiki/Cramer%27s_rule
         * see Applications (Explicit formulas for small systems) specifically the linear system with two equations
         */

        $a1 = $game['A'][0];
        $b1 = $game['B'][0];
        $c1 = $game['Prize'][0] + $prize_increase;
        $a2 = $game['A'][1];
        $b2 = $game['B'][1];
        $c2 = $game['Prize'][1] + $prize_increase;

        $x = (($c1 * $b2) - ($b1 * $c2)) / (($a1 * $b2) - ($b1 * $a2));
        $y = (($a1 * $c2) - ($c1 * $a2)) / (($a1 * $b2) - ($b1 * $a2));

        // return (($x * $a1 + $y * $b1 === $c1) && ($x * $a2 + $y * $b2 === $c2) && 0 < $x && $x < 101 && 0 < $y && $y < 101) ? [$x, $y] : [0, 0];
        return (($x * $a1 + $y * $b1 === $c1) && ($x * $a2 + $y * $b2 === $c2) && 0 < $x && 0 < $y) ? [$x, $y] : [0, 0];
    }
}
