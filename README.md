# Nginx PHP MySQL

Docker running Nginx, PHP-FPM, MySQL and PHPMyAdmin.

**THIS ENVIRONMENT SHOULD ONLY BE USED FOR DEVELOPMENT!**

**DO NOT USE IT IN PRODUCTION!**

## Images to use

* [Nginx](https://hub.docker.com/_/nginx/) (181.85 MB)
* [MySQL](https://hub.docker.com/_/mysql/) (400.2 MB)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/) (635.9 MB)
* [Composer](https://hub.docker.com/r/composer/composer/) (635.7 MB)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/) (102.2 MB)
* [generate-certificate](https://hub.docker.com/r/jacoelho/generate-certificate/) (9.07 MB)

## Start using it

1. Download it :

    ```sh
    git clone https://github.com/nanoninja/docker-nginx-php-mysql.git
    ```

2. Run :

    ```sh
    $ docker-compose up -d
    ```

3. Open your favorite browser :

    * [http://localhost:8000](http://localhost:8000)
    * [https://localhost:3000](https://localhost:3000) (not configured by default)
    * [http://localhost:8080](http://localhost:8080) (phpmyadmin)

## Directory tree

```sh
.
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
    │   ├── src
    │   └── tests
    └── public
        └── index.php
```

## Connecting from PDO

```php
<?php
    $dsn = 'mysql:host=mysql;dbname=test;charset=utf8;port=3306';
    $pdo = new PDO($dsn, 'root', 'root');
?>
```

## Updating composer

```sh
docker run --rm -v $(pwd)/web/app:/app -v ~/.ssh:/root/.ssh composer/composer update
```

## MySQL Container shell access

```sh
docker exec -it mysql bash
```

and

```sh
$ mysql -uroot -proot
```

## Creating database dumps

```sh
docker exec mysql sh -c 'exec mysqldump --all-databases -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/all-databases.sql
```

or

```sh
docker exec mysql sh -c 'exec mysqldump dbname -uroot -p"$MYSQL_ROOT_PASSWORD"' > /some/path/on/your/host/dbname.sql
```

### Example

```sh
docker exec mysql sh -c 'exec mysqldump test -uroot -p"$MYSQL_ROOT_PASSWORD"' > $(pwd)/data/db/dumps/test.sql
```

## Generating SSL certificates

1. Generate certificates

    ```sh
    docker run --rm -v $(pwd)/etc/ssl:/certificates -e "SERVER=localhost" jacoelho/generate-certificate
    ```

2. Configure Nginx

    Edit nginx file **etc/nginx/default.conf** and uncomment the server section.

    ```nginx
    server {
        ...
    }
    ```

## Cleaning project

```sh
./bin/linux/clean.sh $(pwd)
```
