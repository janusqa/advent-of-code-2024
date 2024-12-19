<?php

namespace Janusqa\Adventofcode\Day17;

class Day17B
{

    private $ip = 0;
    private $registers = [];
    private $program = [];
    private $output = [];

    public function run(string $input): void
    {
        [$r, $p] = explode("\n\n", trim($input));

        $pattern_register = '/(A|B|C): (-?\d+)/';
        $pattern_program = '/Program: (.*)/';

        $this->registers = array_reduce(
            explode("\n", $r),
            function ($carry, $n) use ($pattern_register) {
                if (preg_match($pattern_register, $n, $matches)) {
                    $carry[$matches[1]] = (int)$matches[2]; // Map key to value
                }
                return $carry;
            },
            []
        );

        preg_match($pattern_program, $p, $matches);
        $this->program = array_map(fn($n) => (int)$n, explode(',', $matches[1]));

        $candidates = [];

        for ($i = 1; $i < 1025; $i++) {
            $this->ip = 0;
            $this->registers['A'] = $i;
            $this->registers['B'] = 0;
            $this->registers['C'] = 0;
            $this->output = [];
            $this->execute($this->program);
            if ($this->output[0] === $this->program[0]) $candidates[] = $i;
        }

        for ($i = 1; $i < count($this->program); $i++) {
            $new_candidates = [];
            foreach ($candidates as $candidate) {
                for ($bit = 0; $bit < 8; $bit++) {
                    $possible_candidate = ($bit << (7 + 3 * $i)) | $candidate;
                    $this->ip = 0;
                    $this->registers['A'] = $possible_candidate;
                    $this->registers['B'] = 0;
                    $this->registers['C'] = 0;
                    $this->output = [];
                    $this->execute($this->program);
                    if (count($this->output) > $i && $this->output[$i] === $this->program[$i]) $new_candidates[] = $possible_candidate;
                }
            }
            $candidates = $new_candidates;
        }

        echo min($candidates) . PHP_EOL;
    }

    private function execute(array $program): void
    {
        /**
         * Disassembly of program
         * 2,4 -> B = A % 8
         * 1,2 -> B = B ^ 2
         * 7,5 -> C = A / (2 ** B) = A / (1 << B) = A >> B
         * 4,5 -> B = B ^ C
         * 1,3 -> B = B ^ 3
         * 5,5 -> print B % 8
         * 0,3 -> A = A / (2 ** 3) = A / (1 << 3) = A >> 3
         * 3,0 -> if A!== 0 goto 0 else halt!
         */

        while (($this->ip + 1) < count($program)) {
            $this->process($program[$this->ip], $program[$this->ip + 1]);
        }
    }

    private function process(int $instruction, int $operand): void
    {
        if ($operand < 0 || $operand > 7) {
            throw new \InvalidArgumentException("Invalid operand: $operand.");
        }

        if ($instruction === 0) {
            $this->registers['A'] = (int)floor($this->registers['A'] / pow(2, $this->combo($operand)));
        } elseif ($instruction === 1) {
            $this->registers['B'] = $this->registers['B'] ^ $operand;
        } elseif ($instruction === 2) {
            $this->registers['B'] = $this->modulo($this->combo($operand), 8);
        } elseif ($instruction === 3) {
            if ($this->registers['A'] !== 0) {
                $this->ip = $operand;
                return;
            }
        } elseif ($instruction === 4) {
            $this->registers['B'] = $this->registers['B'] ^ $this->registers['C'];
        } elseif ($instruction === 5) {
            $this->output[] = $this->modulo($this->combo($operand), 8);
        } elseif ($instruction === 6) {
            $this->registers['B'] =  (int)floor($this->registers['A'] / pow(2, $this->combo($operand)));
        } elseif ($instruction === 7) {
            $this->registers['C'] =  (int)floor($this->registers['A'] / pow(2, $this->combo($operand)));
        } else {
            throw new \InvalidArgumentException("Invalid instruction: $instruction.");
        }

        $this->ip += 2;
    }

    private function combo(int $operand): int
    {
        if (0 <= $operand && $operand <= 3) {
            return $operand;
        } elseif ($operand === 4) {
            return $this->registers['A'];
        } elseif ($operand === 5) {
            return $this->registers['B'];
        } elseif ($operand === 6) {
            return $this->registers['C'];
        } elseif ($operand === 7) {
            throw new \InvalidArgumentException("Invalid operand: 7 is reserved.");
        } else {
            throw new \InvalidArgumentException("Invalid operand: $operand.");
        }
    }

    private function modulo(int $index, int $wrapAt): int
    {
        return ($index % $wrapAt + $wrapAt) % $wrapAt;
    }
}
