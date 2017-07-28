# Nginx PHP MySQL

Docker running Nginx, PHP-FPM, Composer, MySQL and PHPMyAdmin.

## Overview

1. [Install prerequisites](#install-prerequisites)

    Before installing project make sure the following prerequisites have been met.

2. [Clone the project](#clone-the-project)

    We’ll download the code from its repository on GitHub.

5. [Configure Nginx With SSL Certificates](#configure-nginx-with-ssl-certificates)

    We'll generate and configure SSL certificate for nginx before running server.

3. [Run the application](#run-the-application)

    By this point we’ll have all the project pieces in place.

4. [Use Makefile](#use-makefile) `Recommended`

    When developing, you can use `Makefile` for doing recurrent operations.

5. [Use Docker Commands](#use-docker-commands)

    When running, you can use docker commands for doing recurrent operations.

___

## Install prerequisites

All requisites should be available for your distribution. The most important are :

* [Git](https://git-scm.com/downloads)
* [Docker](https://docs.docker.com/engine/installation/)
* [Docker Compose](https://docs.docker.com/compose/install/)

Check if `docker-compose` is already installed by entering the following command : 

```sh
which docker-compose
```

The following is optional but makes life more enjoyable :

```sh
which make
```

On Ubuntu and Debian these are available in the meta-package build-essential. On other distributions, you may need to install the GNU C++ compiler separately.

```sh
sudo apt install build-essential
```

### Images to use

* [Nginx](https://hub.docker.com/_/nginx/)
* [MySQL](https://hub.docker.com/_/mysql/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [Composer](https://hub.docker.com/r/composer/composer/)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
* [Generate Certificate](https://hub.docker.com/r/jacoelho/generate-certificate/)

You should be careful when installing third party web servers such as MySQL or Nginx.

This project use the following ports :

| Server    | Port |
|-----------|------|
| MySQL     | 3306 |
| Nginx     | 8000 |
| Nginx SSL | 3000 |

---

## Clone the project

To install [Git](http://git-scm.com/book/en/v2/Getting-Started-Installing-Git), download it and install following the instructions : 

```sh
git clone https://github.com/nanoninja/docker-nginx-php-mysql.git
```

Go to the project directory  : 

```sh
cd docker-nginx-php-mysql
```

### Project tree

```sh
.
├── Makefile
├── README.md
├── bin
│   └── linux
│       └── clean.sh
├── data
│   └── db
│       └── mysql
├── docker-compose.yml
├── etc
│   ├── nginx
│   │   └── default.conf
│   ├── php
│   │   └── php.ini
│   └── ssl
└── web
    ├── app
    │   ├── composer.json.dist
    │   ├── phpunit.xml.dist
    │   ├── src
    │   │   └── Foo.php
    │   └── test
    │       ├── FooTest.php
    │       └── bootstrap.php
    └── public
        └── index.php
```

---

## Configure Nginx With SSL Certificates

1. Generate SSL certificates

    ```sh
    sudo docker run --rm -v $(pwd)/etc/ssl:/certificates -e "SERVER=localhost" jacoelho/generate-certificate
    ```

2. Configure Nginx

    Edit nginx file `etc/nginx/default.conf` and uncomment the server section :

    ```sh
    # server {
    #     server_name localhost;
    #
    #     listen 443 ssl;
    #     ...
    # }
    ```

---

## Run the application

1. Copying the composer configuration file : 

    ```sh
    cp web/app/composer.json.dist web/app/composer.json
    ```

2. Start the application :

    ```sh
    sudo docker-compose up -d
    ```

    **Please wait this might take a several minutes...**

    ```sh
    sudo docker-compose logs -f # Follow log output
    ```

3. Open your favorite browser :

    * [http://localhost:8000](http://localhost:8000/)
    * [https://localhost:3000](https://localhost:3000/) ([HTTPS](#configure-nginx-with-ssl-certificates) not configured by default)
    * [phpMyAdmin](http://localhost:8080/) (username: dev, password: dev)

4. Stop and clear services

    ```sh
    sudo docker-compose stop && sudo docker-compose kill && sudo docker-compose rm -f
    ```

---

## Use Makefile

When developing, you can use [Makefile](https://en.wikipedia.org/wiki/Make_(software)) for doing the following operations :

| Name          | Description                           |
|---------------|---------------------------------------|
| apidoc        | Generate documentation of API         |
| clean         | Clean directories for reset           |
| composer-up   | Update php composer                   |
| docker-start  | Create and start containers           |
| docker-stop   | Stop all services                     |
| gen-certs     | Generate SSL certificates for `nginx` |
| mysql-dump    | Create backup of whole database       |
| mysql-restore | Restore backup from whole database    |
| test          | Test application with phpunit         |

### Examples

Start the application : 

```sh
sudo make docker-start
```

Show help :

```sh
make help
```

---

## Use Docker commands

### Updating PHP dependencies with composer

```sh
sudo docker run --rm -v $(pwd)/web/app:/app composer/composer update
```

### Generating PHP API documentation

```sh
sudo docker exec -i $(sudo docker-compose ps -q php) php ./app/vendor/apigen/apigen/bin/apigen generate -s app/src -d app/doc
```

### Testing PHP application with PHPUnit

```sh
sudo docker exec -i $(sudo docker-compose ps -q php) app/vendor/bin/phpunit --colors=always --configuration app/
```

### Handling database

#### MySQL shell access

```sh
sudo docker exec -it mysql bash
```

and

```sh
mysql -u"$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD"
```

#### Backup of database

```sh
mkdir -p data/db/dumps
```

```sh
source .env && sudo docker exec -i mysql mysqldump --all-databases -u"$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD" > "data/db/dumps/db.sql"
```

or

```sh
source .env && sudo docker exec -i mysql mysqldump test -u"$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD" > "data/db/dumps/test.sql"
```

#### Connecting MySQL from [PDO](http://php.net/manual/en/book.pdo.php)

```php
<?php
    try {
        $dsn = 'mysql:host=mysql;dbname=test;charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'dev', 'dev');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
```

---

## Help us !

Any thought, feedback or (hopefully not!)

Developed by [@letvinz](https://twitter.com/letvinz)