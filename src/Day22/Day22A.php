<?php

namespace Janusqa\Adventofcode\Day22;

class Day22A
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));


        $secrets = array_map(fn($n) => (int)$n, $lines);

        for ($i = 0; $i < 2000; $i++) {
            foreach ($secrets as $idx => $secret) {
                $secrets[$idx] = $this->next($secrets[$idx]);
            }
        }

        print_r($secrets);

        echo array_sum($secrets) . PHP_EOL;
    }

    private function next(int $secret): int
    {
        $result = $secret * 64;
        $secret ^= $result;
        $secret = $secret % 16777216;
        $result = floor($secret / 32);
        $secret ^= $result;
        $secret = $secret % 16777216;
        $result = $secret * 2048;
        $secret ^= $result;
        $secret = $secret % 16777216;

        return $secret;
    }
}
