# CesiCar - Api/BackOffice
This documentation helps you start CESICAR Api and BackOffice project. Follow thoses steps to set it ready to use in a local enrironment.
It also provides a great help il focusin on api helpers and understanding of datas.

## ENVIRONMENT
| Language | version     | Description                |
| :-------- | :------- | :------------------------- |
| `php` | `8.1.18` | **Required**. 8.1 minimum |
| `node` | `18.15.0` |  |
| `npm` | `9.5.0` |  |
| `MariaDB` | `15.1` | |

<details>
<summary>INSTALLATION</summary>

## 1/ Clone project
```bash
  git clone repositoryName
```

## 2/ install vendors
```bash
  composer install
```
## 3/ install nodes
```bash
npm install
```

## 4/ Create .env
```bash
Create file .env.local in your root folder
```
Add this line with your DB parametes : 
```bash
DATABASE_URL="mysql://login:password@127.0.0.1:3306/databasename?serverVersion=yourmysqlversion"
```

## 5/ Create the Database
```bash
php bin/console doctrine:database:create
```

## 6/ Apply DB migrations :
```bash
php bin/console doctrine:s:u --force
```

## 7/ Run fixtures to furnish the databases with starting datas
```bash
php bin/console doctrine:fixtures:load
```

## 8/ Start API / BackOffice
```bash
symfony server:start
```

## 9/ Generate JWT keys
```bash
php bin/console lexik:jwt:generate-keypair
```

## Optional/ Create first admin user
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
## Optional/Hash your first password
```bash
php bin/console security:hash-password
```
</details>

<details>
<summary>UPDATES / PULL</summary>

Each time you update the 'develop' project by :
```bash
git pull
```

Dont forget to watch changes, it may be needed to do so :
```bash
composer install && npm install
```

Also you may want to check if migrations have to be done
```bash
php bin/console doctrine:s:u --dump-sql
```

Dont forget to generate your JWT Keys
```bash
php bin/console lexik:jwt:generate-keypair
```
</details>


<details>
<summary>ROUTES</summary>

## Access BackOffice
127.0.0.1:8000/admin

connect with your credentials or this admin :
  login : florent.gallou@viacesi.fr
  password : password

## Access api
127.0.0.1:8000/api
-> help : https://symfonycasts.com/screencast/api-platform/json-ld

## Access api using helper
127.0.0.1:8000/_profiler
-> help : https://symfonycasts.com/screencast/api-platform/profiler

## Access api login
127.0.0.1:8000/api/login

## Access api login
127.0.0.1:8000/api/logout

### Api get all Travels
127.0.0.1:8000/api/travels
```bash

[
  {
    "id": 0,
    "toCesi": true,
    "position": [
      "string"
    ],
    "departure_date": "2023-04-27T23:00:09.018Z",
    "user": {
      "name": "string"
    }
  }
]
```
- toCesi (boulean) :
  - true = travel TO CESI
  - false = travel FROM CESI

- position (json array [number, number]) :
  - if toCesi = true -> position = position from where you start to go to CESI
  - if toCesi = false -> position = position where you go when leaving CESI

- departure_date = datetime from when travel starts
  - to know travel length, calculate time with Km between CESI and position

- user.name = first_name.' '.last_name (of driver)

</details>

<details>
<summary>ROUTES</summary>

## API JWT
If you log correctly with : 
127.0.0.1:8000/api/login

You'll get a Tocken like this :
```bash
{
	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2ODMxMTc4NTIsImV4cCI6MTY4MzEyMTQ1Miwicm9sZXMiOlsiUk9MRV9TVVBFUkFETUlOIiwiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiZmxvcmVudC5nYWxsb3VAdmlhY2VzaS5mciJ9.GlrP61Tv_qI3gI3MKEOuLT9QoFob-Iu8lp2MwlCvQ9RiTLFFvVhCaq8ZvnFspgp-wrmrFc6VBfOsZ3_p8EgS6JLLL367QobCLRVWkdskMRpreaE0Fqwdu84P2xQX9ArCnxJbpbffE6ISIDV7T_t1K3pGwMzC4dcCRAVJMr2LtRgR0uV70-OT4dbqI_RnEYxN7rnAdYtKNblVZ54dFbjs4SveBXJD89WJ-IVbyM-rGwR25sHZkfirFGxbROuvI8oZy8JBt738kQbJCRq4bgdzEPVCpN_UpNiWJdlKdJPvoo8-M78NjYGE04x2si3Ms3HT5hDtzk7VoMFo3JouPAQibA"
}
```
You can decode this tocken here :
https://jwt.io/#debugger-io

Just copy/paste the api tocken to see result
</details>
