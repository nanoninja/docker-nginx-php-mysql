# Makefile for Docker Nginx PHP Composer MySQL

# config environment
include .env

# MySQL
MYSQL_DUMPS_DIR=data/db/dumps

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  apidoc              Generate documentation of API"
	@echo "  clean               Clean directories for reset"
	@echo "  composer-up         Update php composer"
	@echo "  docker-start        Create and start containers"
	@echo "  docker-stop         Stop all services"
	@echo "  docker-sweep        Sweep old containers and volumes"
	@echo "  gen-certs            Generate SSL certificates"
	@echo "  mysql-dump          Create backup of whole database"
	@echo "  mysql-restore       Restore backup from whole database"
	@echo "  test                Test application"

init:
	@cp -n $(shell pwd)/web/app/composer.json.dist $(shell pwd)/web/app/composer.json

apidoc:
	@docker exec -i $(shell docker-compose ps -q php) php ./app/vendor/apigen/apigen/bin/apigen generate -s app/src -d app/doc

clean:
	@rm -Rf data/db/mysql/*
	@rm -Rf $MYSQL_DUMPS_DIR/*
	@rm -Rf web/app/vendor
	@rm -Rf web/app/composer.lock
	@rm -Rf web/app/doc
	@rm -Rf web/app/report
	@rm -Rf etc/ssl/*

composer-up:
	@docker run --rm -v $(pwd)/web/app:/app composer/composer update

docker-start: init
	@echo "Docker is running..."
	docker-compose up -d;

docker-stop:
	docker-compose stop
	docker-compose kill
	docker-compose rm -f
	@make clean

docker-sweep:
	@docker ps -aq | xargs docker rm
	@docker volume ls -qf dangling=true | xargs docker volume rm

gen-certs:
	@docker run --rm -v $(pwd)/etc/ssl:/certificates -e "SERVER=localhost" jacoelho/generate-certificate

mysql-dump:
	@mkdir -p $(MYSQL_DUMPS_DIR)
	@docker exec -i $(shell docker-compose ps -q mysqldb) mysqldump --all-databases -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" > $(MYSQL_DUMPS_DIR)/db.sql

mysql-restore:
	@docker exec -i mysql mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/db.sql

test:
	@docker exec -i $(shell docker-compose ps -q php) app/vendor/bin/phpunit --colors=always --configuration app/

tearDown:
	chown -Rf "$(shell whoami):$(shell id -g -n $(whoami))" data web/app

.PHONY: clean