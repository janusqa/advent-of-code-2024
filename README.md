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
