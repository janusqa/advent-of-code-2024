<?php

namespace Janusqa\Adventofcode\Day17;

class Day17A
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

        // print_r($this->registers);
        // print_r($this->ip . PHP_EOL);
        // print_r($this->program);


        // $this->registers['A'] = 2024;
        // $this->registers['B'] = 2024;
        // $this->registers['C'] = 43690;
        // if (count($this->output) > 0) $this->output = [];
        // $this->test([0, 1, 5, 4, 3, 0]);

        $this->execute($this->program);

        echo implode(",", $this->output) . PHP_EOL;
    }

    private function process(int $instruction, int $operand): void
    {
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
        }
    }

    private function modulo(int $index, int $wrapAt): int
    {
        return ($index % $wrapAt + $wrapAt) % $wrapAt;
    }

    private function execute(array $program): void
    {
        while (($this->ip + 1) < count($program)) {
            $this->process($program[$this->ip], $program[$this->ip + 1]);
        }
    }
}
