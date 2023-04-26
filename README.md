# CesiCar

## First install vendors :
composer install

## Create the Database
php bin/console doctrine:database:create

## See DB columns modifications
php bin/console doctrine:s:u --dump-sql

## Apply DB columns mdoifciations :
php bin/console doctrine:s:u --force

# Create first admin user
in database, add first row in user :
- first_name
- last_name
- gender -> homme or femme or autre
- email -> for login
- password -> hashed with delow php command
- roles -> ["ROLE_ADMIN"]
- driver -> 0 or 1
- is_verified -> 0 or 1
- created_at and updated_at -> set now value
## Hash your first password
php bin/console security:hash-password

## Access BackOffice
/admin

## Access api
/api