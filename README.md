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

 ## 2/ Create .env
  ```bash
  Create file .env.local in your root folder
  ```
Add this line with your DB parametes :
  ```bash
    DATABASE_URL="mysql://api_cesicar:api_cesicar@db:3306/api_cesicar?serverVersion=mariadb-10.6.7"
  ```

  ## 3/ build docker
  ```bash
    cd .docker
  ```
  ```bash
    docker-compose build www
  ```
  ```bash
    docker-compose up -d
  ```
  ```bash
    bin/install
  ```

  ## 4/ up the project 
  ```bash
  docker-compose up -d
  ```

  ## link of the projet locally
  - projet - http://localhost:8000/
  - phpmyadmin - http://localhost:8080/


  ## 5/ Generate JWT keys
  ```bash
  docker-compose exec php bin/console lexik:jwt:generate-keypair
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
  docker-compose exec composer install && npm install
  ```

  Also you may want to check if migrations have to be done
  ```bash
  docker-compose exec php bin/console doctrine:s:u --force
  ```

  Dont forget to generate your JWT Keys
  ```bash
   docker-compose exec php bin/console lexik:jwt:generate-keypair
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
  <summary>AUHENTIFICATION</summary>

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

<details>
<summary>API FILTERS</summary>

  Filters are made to be chainable, you can add filters in what order you want and change paremeters as you need

  ## Travel filters
  To get only travels that goes to CESI
  http://127.0.0.1:8000/api/travels?toCesi=true

  To get only travels that goes back from CESI
  http://127.0.0.1:8000/api/travels?toCesi=false

  To get only travels that goes back from CESI with dates before july 2023
  http://127.0.0.1:8000/api/travels?toCesi=true&departure_date%5Bbefore%5D=2023-07

  To get only travels that goes back from CESI with dates after july 2023
  http://127.0.0.1:8000/api/travels?toCesi=true&departure_date%5Bbefore%5D=2023-07

  To get only travels that goes back from CESI with dates between mai 2023 and july 2023
  http://127.0.0.1:8000/api/travels?toCesi=true&departure_date%5Bbefore%5D=2023-07&departure_date%5Bafter%5D=2023-05

</details>

<details>
<summary>DOCKER</summary>

### 1/ Créer le fichier Dockerfile dans la racide du projet symfony

### 2/ Builder l'image
``` bash
docker build -t cesicar-api-bo .
```

### 3/ Lancer l'image
Attention sous linux à bien arrêter le serveur apache au besoin : sudo service apache2 stop
``` bash
docker run -d -p 80:80 --name img-cesicarboapi cesicar-api-bo
```

### 4/ Copier fichier php.ini de l'image en local pour pouvoir configurer le fichier
``` bash
docker cp img-cesicarboapi:/usr/local/etc/php ./.docker/php
```

### 5/ Arrêter l'image
``` bash
docker ps
docker stop 9d91536e0fca
```

<!-- ### 6/ Lancer l'image avec la relation entre le fichier ini en local et celui dans l'image
``` bash
sudo docker run -d -p 80:80 -v $(pwd)/usr/local/etc/php:/docker/ --name my-php-app project-php
``` -->

### 7/ Créer le fichier docker-compose.yml en racine

### 8/ Lancer docker compose
``` bash
sudo docker compose up
```

### 9/ Pour symfony changer la source du point d'entrée index.php dans le dockerfile :
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

### 10/ Add extensions in Dockerfile:
RUN docker-php-ext-install mysqli pdo pdo_mysql

### 11/ Décommenter les extensions dans le fichier local docker/php.ini
extension=mysqli
extension=pdo_mysql

doc traefic
Gérer les certificats SSL
--certificatesresolvers.myresolver.acme.httpchallenge=true
--certificatesresolvers.myresolver.acme.httpchallenge.entrypoint=web
--certificatesresolvers.myresolver.acme.caserve=https://acme-staging-v02.api-letsencrypt.org/diectory

</details>
