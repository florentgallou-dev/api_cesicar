# CesiCar

## First install vendors :
composer install

## See DB columns modifications
php bin/console doctrine:s:u --dump-sql

## Apply DB columns mdoifciations :
php bin/console doctrine:s:u --force