#usr/bin/sh

rm -rf ./web
docker run --rm -it -v $PWD:/app --user $(id -u):$(id -g) composer create-project --prefer-dist laravel/laravel web
