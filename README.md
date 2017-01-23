# Nginx PHP MySQL

Docker running Nginx, PHP-FPM, MySQL and PHPMyAdmin.

**THIS ENVIRONMENT SHOULD ONLY BE USED FOR DEVELOPMENT!**

**DO NOT USE IT IN PRODUCTION!**

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
    $ git clone https://github.com/nanoninja/docker-nginx-php-mysql.git
    ```

2. Run :

    ```sh
    $ docker-compose up -d
    ```

3. Open your favorite browser :

    * [http://localhost:8000](http://localhost:8000/)
    * [https://localhost:3000](https://localhost:3000/) ([HTTPS](https://github.com/nanoninja/docker-nginx-php-mysql#generating-ssl-certificates) not configured by default)
    * [phpMyAdmin](http://localhost:8080/) (user: dev, pass: dev)

## Directory tree

```sh
├── README.md
├── bin
│   └── linux
│       └── clean.sh
├── data
│   └── db
│       ├── dumps
│       └── mysql
├── docker-compose.yml
├── etc
│   ├── nginx
│   │   └── default.conf
│   ├── php
│   │   └── php.ini
│   └── ssl
└── web
    ├── app
    │   ├── composer.json
    │   ├── phpunit.xml.dist
    │   ├── src
    │   │   └── Foo.php
    │   └── test
    │       ├── FooTest.php
    │       └── bootstrap.php
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
$ docker run --rm -v $(pwd)/web/app:/app -v ~/.ssh:/root/.ssh composer/composer update
```

## MySQL Container shell access

```sh
$ docker exec -it mysql bash
```

and

```sh
$ mysql -uroot -proot
```

## Creating database dumps

```sh
$ docker exec mysql sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/all-databases.sql
```

or

```sh
$ docker exec mysql sh -c 'exec mysqldump dbname -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/dbname.sql
```

### Example

```sh
$ docker exec mysql sh -c 'exec mysqldump test -uroot -p"$MYSQL_ROOT_PASSWORD"' > $(pwd)/data/db/dumps/test.sql
```

## Generating SSL certificates

1. Generate certificates

    ```sh
    $ docker run --rm -v $(pwd)/etc/ssl:/certificates -e "SERVER=localhost" jacoelho/generate-certificate
    ```

2. Configure Nginx

    Edit nginx file **etc/nginx/default.conf** and uncomment the server section.

    ```nginx
    # server {
    #     ...
    # }
    ```

## Generating API Documentation

```sh
./web/app/vendor/apigen/apigen/bin/apigen generate -s web/app/src -d web/app/doc
```

## Cleaning project

```sh
$ ./bin/linux/clean.sh $(pwd)
```
