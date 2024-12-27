<?php

namespace Janusqa\Adventofcode\Day22;

class Day22B
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $secrets = array_map(fn($n) => [['secret' => (int)$n, 'price' => $n % 10, 'change' => 0]], $lines);

        foreach ($secrets as &$buyer) {
            $secret = $buyer[0]['secret'];
            for ($i = 1; $i <= 2000; $i++) {
                $secret = $this->next($secret);
                $price = $secret % 10;
                $change = $price - $buyer[$i - 1]['price'];
                $buyer[] = ['price' => $price, 'change' => $change];
            }
        }

        $prices = array_map(fn($n) => array_map(fn($m) => $m['price'], $n), $secrets);
        $changes = array_map(fn($n) => array_map(fn($m) => $m['change'], $n), $secrets);


        $sequence_map = [];

        foreach ($changes as $idx => $buyer) {
            $seq_start = 1;
            $seq_end = 4;
            while ($seq_end < count($buyer)) {
                $sequence = [$buyer[$seq_start], $buyer[$seq_start + 1], $buyer[$seq_start + 2], $buyer[$seq_start + 3]];
                $skey = "{$buyer[$seq_start]},{$buyer[$seq_start + 1]},{$buyer[$seq_start + 2]},{$buyer[$seq_start + 3]}";
                if (!isset($sequence_map[$idx][$skey])) {
                    $sequence_map[$idx][$skey] = $prices[$idx][$seq_start + count($sequence) - 1];
                }
                $seq_start++;
                $seq_end++;
            }
        }

        $bananas_total = 0;

        foreach ($sequence_map[0] as $skey => $price) {
            $running_total = $price;
            for ($i = 1; $i < count($sequence_map); $i++) {
                $running_total += $sequence_map[$i][$skey] ?? 0;
            }
            $bananas_total = max($running_total, $bananas_total);
        }

        echo $bananas_total . PHP_EOL;
    }

    private function next(int $secret): int
    {
        $secret ^= ($secret * 64) % 16777216;
        $secret ^= floor($secret  / 32) % 16777216;
        $secret ^= ($secret * 2048) % 16777216;

        return $secret;
    }
}
