#!/usr/bin/env bash

ROOT_PATH=$1

if [[ ! -d "$ROOT_PATH" && ! -L "$ROOT_PATH" ]]; then
    echo "No such file or directory"
    exit 1
fi

DATA_PATH=$ROOT_PATH/data
ETC_PATH=$ROOT_PATH/etc
WEB_PATH=$ROOT_PATH/web
APP_PATH=$WEB_PATH/app

rm -Rf $DATA_PATH/db/mysql/*
rm -Rf $DATA_PATH/dumps/*
rm -Rf $APP_PATH/vendor
rm -Rf $APP_PATH/composer.lock
rm -Rf $APP_PATH/doc
rm -Rf $ETC_PATH/ssl/*

# remove exited containers
docker rm $(docker ps -a -f status=exited -q)
docker volume rm $(docker volume ls -qf dangling=true)
