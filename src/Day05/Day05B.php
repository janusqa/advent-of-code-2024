<?php

namespace Janusqa\Adventofcode\Day05;

class Day05B
{

    public function run(string $input): void
    {
        $lines = explode("\n", trim($input));

        $pattern_rules = '/(?:\d+\|\d+)/';
        $pattern_updates = '/\d+(?:,\d+)+/';

        $rules = [];
        $updates = [];

        $middle_number_sum = 0;

        foreach ($lines as $line) {
            if (preg_match($pattern_rules, $line, $matches)) {
                $rule_parts = explode("|", $matches[0]);
                $rules[$rule_parts[0]][] = $rule_parts[1];
            } elseif (preg_match($pattern_updates, $line, $matches)) {
                $updates[] = explode(",", $matches[0]);
            }
        }

        foreach ($updates as $update) {
            $correctly_ordered = true;
            $skipped_pages = [];
            foreach ($update as $index => $page) {
                if (array_key_exists($page, $rules)) {
                    if (!empty(array_diff(array_slice($update, $index + 1), $rules[$page]))) {
                        $correctly_ordered = false;
                        break;
                    }

                    if (count($skipped_pages) > 0 && empty(array_diff($skipped_pages, $rules[$page]))) {
                        $correctly_ordered = false;
                        break;
                    }
                } else {
                    $skipped_pages[] = $page;
                }
            }

            if (!$correctly_ordered) {
                $ordered = $this->bubbleSort($update, $rules);
                $middle_index = floor(count($ordered) / 2);
                $middle_number_sum += $ordered[$middle_index];
            }
        }

        echo ($middle_number_sum . PHP_EOL);
    }

    private function bubbleSort(array $array, array $rules): array
    {
        $n = count($array);

        for ($i = 0; $i < $n - 1; $i++) {
            // Flag to check if any swapping happened
            $swapped = false;

            // Traverse the array up to the unsorted portion
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if (array_key_exists($array[$j + 1], $rules) && in_array($array[$j], $rules[$array[$j + 1]])) {
                    // Swap adjacent elements if they are in the wrong order
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;

                    $swapped = true;
                }
            }

            // If no swapping happened, the array is already sorted
            if (!$swapped) {
                break;
            }
        }

        return $array;
    }
}
