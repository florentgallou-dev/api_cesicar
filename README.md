# CesiCar

## First install vendors :
composer install

## Create the Database
php bin/console doctrine:database:create

## See DB columns modifications
php bin/console doctrine:s:u --dump-sql

## Apply DB columns mdoifciations :
php bin/console doctrine:s:u --force