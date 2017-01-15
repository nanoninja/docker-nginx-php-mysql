#!/bin/bash

ROOT_PATH=$1

if [[ ! -d "$ROOT_PATH" && ! -L "$ROOT_PATH" ]]; then
    echo "No such file or directory"
fi

WEB_PATH=$ROOT_PATH/web
DATA_PATH=$ROOT_PATH/data
ETC_PATH=$ROOT_PATH/etc
APP_PATH=$WEB_PATH/app

rm -Rf $DATA_PATH/db/mysql/*
rm -Rf $DATA_PATH/dumps/*
rm -Rf $APP_PATH/vendor
rm -Rf $APP_PATH/composer.lock

docker rm -f $(docker ps -aq)
docker volume rm $(docker volume ls -qf dangling=true)
