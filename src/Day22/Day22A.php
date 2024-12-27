<?php

namespace Janusqa\Adventofcode\Day22;

class Day22A
{
    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $secrets = array_map(fn($n) => (int)$n, $lines);

        $result = 0;
        foreach ($secrets as $secret) {
            for ($i = 0; $i < 2000; $i++) {
                $secret = $this->next($secret);
            }
            $result += $secret;
        }

        echo $result . PHP_EOL;
    }

    private function next(int $secret): int
    {
        $secret ^= ($secret * 64) % 16777216;
        $secret ^= floor($secret  / 32) % 16777216;
        $secret ^= ($secret * 2048) % 16777216;

        return $secret;
    }
}
