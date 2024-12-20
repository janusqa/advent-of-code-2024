# Advent of Code 2024
Language: PHP

# Description
Advent of Code is an Advent calendar of small programming puzzles for a variety of skill sets and skill levels that can be solved in any programming language you like. People use them as a speed contest, interview prep, company training, university coursework, practice problems, or to challenge each other.

You don't need a computer science background to participate - just a little programming knowledge and some problem solving skills will get you pretty far. Nor do you need a fancy computer; every problem has a solution that completes in at most 15 seconds on ten-year-old hardware.<br/>
Source: [Advent of Code Github Topic](https://github.com/topics/advent-of-code)<br/>

[Day 01](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day01)<br/>
[Day 02](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day02)<br/>
[Day 03](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day03)<br/>
[Day 04](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day04)<br/>
[Day 05](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day05)<br/>
[Day 06](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day06)<br/>
[Day 07](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day07)<br/>
[Day 08](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day08)<br/>
[Day 09](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day09)<br/>
[Day 10](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day10)<br/>
[Day 11](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day11)<br/>
[Day 12](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day12)<br/>
[Day 13](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day13)<br/>
[Day 14](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day14)<br/>
[Day 15](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day15)<br/>
[Day 16](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day16)<br/>
[Day 17](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day17)<br/>
[Day 18](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day18)<br/>
[Day 19](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day19)<br/>
[Day 20](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day20)<br/>
[Day 21](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day21)<br/>
[Day 22](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day22)<br/>
[Day 23](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day23)<br/>
[Day 24](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day24)<br/>
[Day 25](https://github.com/janusqa/advent-of-code-2024/tree/main/src/Day25)<br/>
<br/>
Keywords: aoc adventofcode

### Install Composer
- We will download it to a temporary place and install it there
  - $ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  - $ php -r "if (hash_file('sha384', 'composer-setup.php') 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  - $ php composer-setup.php
  - $ php -r "unlink('composer-setup.php');"
- mv composer.phar /usr/local/bin/composer
- Now run "composer" in order to run Composer instead of "php composer.phar".
- sudo composer self-update // to update

### setup in a project
- cd to project directory
- composer init
- adjust vendor/autoload.php to your liking

### Install a package with Composer
 - $php composer.phar require htmlburger/carbon-fields
