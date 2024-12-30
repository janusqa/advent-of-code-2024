<?php
// run.php

require_once __DIR__ . '/../vendor/autoload.php';

// Get day and part from command line arguments
$day = isset($argv[1]) ? str_pad((int)$argv[1], 2, '0', STR_PAD_LEFT) : null;
$part = isset($argv[2]) ? strtolower($argv[2]) : null;

if (!$day || !in_array($part, ['a', 'b', 'c'])) {
    echo "Usage: php run.php <day> <part>\n";
    echo "Example: php run.php 1 a\n";
    exit(1);
}

// Build the class name dynamically
$dayClass = "Janusqa\\Adventofcode\\Day{$day}\\Day{$day}" . strtoupper($part);
if (!class_exists($dayClass)) {
    echo "Solution for Day {$day} Part {$part} not found.\n";
    exit(1);
}

// Build the file path for the solution and input file
$inputFile = __DIR__ . "/Day{$day}/input.txt";
if (!file_exists($inputFile)) {
    echo "Input file for Day {$day} not found.\n";
    exit(1);
}

// Load the input
$input = file_get_contents($inputFile);

// Run the solution file and pass the input
echo "Running Advent of Code 2024 - Day {$day} Part {$part}...\n";
$dayInstance = new $dayClass();
$dayInstance->run($input);
