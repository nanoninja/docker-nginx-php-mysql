#usr/bin/sh

VERSION=${1:-7.25.0}

mkdir tmp_laravel_tar
rm -rf ./web
mkdir web
wget -c -O - https://github.com/laravel/laravel/archive/v"$VERSION".tar.gz | tar -xz -C tmp_laravel_tar/
cp -r tmp_laravel_tar/laravel-"$VERSION"/* ./web
rm -rf tmp_laravel_tar

envsubst '$$DB_HOST $$DB_PASSWORD' < laravel.env.template > ./web/.env

docker run --rm -it -v $PWD/web:/app --user $(id -u):$(id -g) composer install
