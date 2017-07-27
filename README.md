# Nginx PHP MySQL

Docker running Nginx, PHP-FPM, Composer, MySQL and PHPMyAdmin.

## Images to use

* [Nginx](https://hub.docker.com/_/nginx/)
* [MySQL](https://hub.docker.com/_/mysql/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [Composer](https://hub.docker.com/r/composer/composer/)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
* [Generate Certificate](https://hub.docker.com/r/jacoelho/generate-certificate/)

## Start using it

1. Download it :

    ```sh
    git clone https://github.com/nanoninja/docker-nginx-php-mysql.git

    cd docker-nginx-php-mysql
    ```

2. Copying the composer configuration file : 

    ```sh
    cp web/app/composer.json.dist web/app/composer.json
    ```

3. Start :

    ```sh
    docker-compose up -d
    ```

    **Please wait this might take a several minutes...**

4. Open your favorite browser :

    * [http://localhost:8000](http://localhost:8000/)
    * [https://localhost:3000](https://localhost:3000/) ([HTTPS](https://github.com/nanoninja/docker-nginx-php-mysql#generating-ssl-certificates) not configured by default)
    * [phpMyAdmin](http://localhost:8080/) (user: dev, pass: dev)

5. Stop :

    ```sh
    docker-compose stop && docker-compose kill && docker-compose rm -f
    ```

## Makefile

When developing, you can use the [Makefile](https://en.wikipedia.org/wiki/Make_(software)) for doing the following operations :

| Name          | Description                             |
|---------------|-----------------------------------------|
| apidoc        | Generate documentation of API           |
| clean         | Clean directories for reset             |
| composer-up   | Update php composer                     |
| docker-start  | Create and start containers             |
| docker-stop   | Stop all services                       |
| docker-sweep  | Sweep old containers and volumes        |
| gen-certs     | Generate SSL certificates for **nginx** |
| mysql-dump    | Create backup of whole database         |
| mysql-restore | Restore backup from whole database      |
| test          | Test application with phpunit           |

## Directory tree

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

## Connecting from PDO

```php
<?php
    $dsn = 'mysql:host=mysql;dbname=test;charset=utf8;port=3306';
    $pdo = new PDO($dsn, 'dev', 'dev');
?>
```

## Updating composer

```sh
docker run --rm -v $(PWD)/web/app:/app composer/composer update
```

## MySQL Container shell access

```sh
docker exec -it mysql bash
```

and

```sh
mysql -uroot -proot
```

## Creating database dumps

```sh
source .env && docker exec -i $(docker-compose ps -q mysqldb) mysqldump --all-databases -u"$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD" > "$MYSQL_DUMPS_DIR/db.sql"
```

or

```sh
source .env && docker exec -i $(docker-compose ps -q mysqldb) mysqldump test -u"$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD" > "$MYSQL_DUMPS_DIR/test.sql"
```

## Generating SSL certificates

1. Generate certificates

    ```sh
    docker run --rm -v $(PWD)/etc/ssl:/certificates -e "SERVER=localhost" jacoelho/generate-certificate
    ```

2. Configure Nginx

    Edit nginx file **etc/nginx/default.conf** and uncomment the server section :

    ```nginx
    # server {
    #     ...
    # }
    ```

## Generating API Documentation

```sh
docker exec -i $(docker-compose ps -q php) php ./app/vendor/apigen/apigen/bin/apigen generate -s app/src -d app/doc
```

## Cleaning project

```sh
./bin/linux/clean.sh $(pwd)
```
