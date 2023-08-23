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
  <summary>DOCKER - INSTALLATION</summary>

  ## 1/ Clone project localy
  ```bash
    git clone repositoryName
  ```

  ## 2/ In project folder, create a new .env.local file and paste connexion parameters to container docker database
  ```bash
  Create file .env.local in your root folder
  ```
  Add this line with your DB parametes :
  ```bash
    DATABASE_URL="mysql://api_cesicar:api_cesicar@db:3306/api_cesicar?serverVersion=mariadb-10.6.7"
  ```

  ## 3/ build docker
  ```bash
    docker compose up --build
  ```

  ## 4/ Connect to web container and lauch script install to :
  - composer install
  - npm install
  - start npm
  - lauch migrations
  - lauch seeder
  - generate jwt key

  ```bash
    docker exec -it identifiantContainer bash
    ./install
  ```
  ## All system should work 🎉

  ## link of the projet locally
  - projet - http://localhost:8000/
  - phpmyadmin - http://localhost:8080/

</details>

<details>
  <summary>DOCKER - UPDATES / PULL</summary>

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
  <summary>NO-DOCKER - INSTALLATION</summary>

  ### 1/ Clone project
  ```bash
    git clone repositoryName
  ```

  -> Then go in the project folder

  ### 2/ Create .env
  ```bash
  Create file .env.local in your project root folder
  ```
  Add this line with your DB parametes :
  ```bash
    DATABASE_URL="mysql://api_cesicar:api_cesicar@db:3306/api_cesicar?serverVersion=mariadb-10.6.7"
  ```

  ### 3/ Composer libraries
  ```bash
    composer install
  ```

  ### 4/ Npm libraries
  ```bash
    npm install
  ```

  ### 5/ Create DataBase
  ```bash
    php bin/console doctrine:database:create
  ```

  ### 6/ Migrate database
  ```bash
    php bin/console doctrine:s:u --force
  ```

  ### 7/ Seed database
  ```bash
    php bin/console doctrine:fixtures:load
  ```
  ### 8/ Start server
  ```bash
    symfony server:start
    ou
    php bin/console server:start
  ```

  ### 9/ Generate JWT keys
  ```bash
    php bin/console lexik:jwt:generate-keypair
  ```

  ### link of the projet locally
  - BO - http://localhost:8000/admin
  - api - http://localhost:8080/api

  ### HELP/ Hash your first password
  ```bash
  php bin/console security:hash-password
  ```
</details>

<details>
  <summary>NO-DOCKER - UPDATES / PULL</summary>

  ### 0/ In project folder, before anything check witch branch your on
  ```bash
    git status
  ```
  ### 1/ Go to develop branch
  ```bash
    git checkout develop
  ```
  ### 2/ Fetch differences - read report
  ```bash
    git fetch
  ```

  ### 3/ Pull updates - read report
  ```bash
    git pull
  ```

  ### 4/ If needed update libraries
  ```bash
    composer install && npm install
  ```

  ### 5/ Delete the whole database and recreate it
  ```bash
    php bin/console doctrine:database:create
  ```

  ### 6/ Migrate database
  ```bash
    php bin/console doctrine:s:u --force
  ```

  ### 7/ Seed database
  ```bash
    php bin/console doctrine:fixtures:load
  ```
</details>

<details>
  <summary>ROUTES</summary>

  ### Access BackOffice
  127.0.0.1:8000/admin

  connect with your credentials or this admin :
    login : florent.gallou@viacesi.fr
    password : password

  ### Access api
  127.0.0.1:8000/api
  -> help : https://symfonycasts.com/screencast/api-platform/json-ld

  ### Access api using helper
  127.0.0.1:8000/_profiler
  -> help : https://symfonycasts.com/screencast/api-platform/profiler

  ### Access api register
  BO Controller : src/Controller/SecurityControlle/register()
  127.0.0.1:8000/api/register
  send parameters
    -> email
    -> password

  ### Access api login
  BO Controller : src/Controller/SecurityControlle/login() automatic JWT generator
  127.0.0.1:8000/api/login
  send parameters
    -> username
    -> password

  ### Access api connected user
  127.0.0.1:8000/api/me
    -> get    - read
    -> patch  - update

  ### Access api login
  127.0.0.1:8000/api/logout

  ### Api get all Travels
  127.0.0.1:8000/api/travels
  ```bash
  [
    {
			"@id": "\/api\/travel\/21",
			"@type": "Travel",
			"id": 21,
			"toCesi": true,
			"address": {
				"label": "10 Rue Saint Laurent 27700 Heuqueville",
				"housenumber": "10",
				"id": "27337_0082_00010",
				"name": "10 Rue Saint Laurent",
				"postcode": "27700",
				"citycode": "27337",
				"position": [
					49.286074,
					1.343469
				],
				"city": "Heuqueville",
				"context": "27, Eure, Normandie",
				"street": "Rue Saint Laurent"
			},
			"departure_date": "2023-10-22T03:13:00+02:00",
			"user": {
				"@id": "\/api\/users\/28",
				"@type": "User",
				"name": "Florent Gallou"
			}
		},
  ]
  ```
  - toCesi (boulean) :
    - true = travel TO CESI
    - false = travel FROM CESI

  - position (json array [geo api values]) :
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

  You'll get a Token like this :
  ```bash
  {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2ODMxMTc4NTIsImV4cCI6MTY4MzEyMTQ1Miwicm9sZXMiOlsiUk9MRV9TVVBFUkFETUlOIiwiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiZmxvcmVudC5nYWxsb3VAdmlhY2VzaS5mciJ9.GlrP61Tv_qI3gI3MKEOuLT9QoFob-Iu8lp2MwlCvQ9RiTLFFvVhCaq8ZvnFspgp-wrmrFc6VBfOsZ3_p8EgS6JLLL367QobCLRVWkdskMRpreaE0Fqwdu84P2xQX9ArCnxJbpbffE6ISIDV7T_t1K3pGwMzC4dcCRAVJMr2LtRgR0uV70-OT4dbqI_RnEYxN7rnAdYtKNblVZ54dFbjs4SveBXJD89WJ-IVbyM-rGwR25sHZkfirFGxbROuvI8oZy8JBt738kQbJCRq4bgdzEPVCpN_UpNiWJdlKdJPvoo8-M78NjYGE04x2si3Ms3HT5hDtzk7VoMFo3JouPAQibA"
  }
  ```
  You can decode this token here :
  https://jwt.io/#debugger-io

  Just copy/paste the api token to see result

  ## Use JWT Token to get api/me

  add to the request a Auth Types - Bearer Token : your-jwt-token
  EX : with Axios

  ``` js
    const headerDatas = {
      headers: {
        'Content-Type': 'application/ld+json',
        'Authorization': `Bearer ${token}`
      }
    };

    axios.get(`${process.env.API_URL}/me`,
                  headerDatas
                ).then(console.log).catch(console.log);
  ```
  ## For help :
  Postman Tests : https://www.youtube.com/watch?v=SKswJH7_plQ&ab_channel=ValentinDespa
  StackOverflow : https://stackoverflow.com/questions/40988238/sending-the-bearer-token-with-axios
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
