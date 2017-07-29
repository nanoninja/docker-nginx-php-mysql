# Makefile for Docker Nginx PHP Composer MySQL

include .env

# MySQL
MYSQL_DUMPS_DIR=data/db/dumps

# Paths
ROOT_PATH=/var/www/html

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
	@echo "  gen-certs           Generate SSL certificates"
	@echo "  logs                Follow log output"
	@echo "  mysql-dump          Create backup of whole database"
	@echo "  mysql-restore       Restore backup from whole database"
	@echo "  test                Test application"

init:
	@$(shell cp -n $(shell pwd)/web/app/composer.json.dist $(shell pwd)/web/app/composer.json 2> /dev/null)

travis:
	@docker exec $(shell docker-compose ps -q php) php -r 'echo getcwd(); var_dump(file_exists("app/vendor/apigen/apigen/bin/apigen")); var_dump(file_exists("web/app/vendor/apigen/apigen/bin/apigen");'

apidoc:
	@docker exec $(shell docker-compose ps -q php) app/vendor/apigen/apigen/bin/apigen generate -s app/src -d app/doc
	@make resetOwner

clean:
	@rm -Rf data/db/mysql/*
	@rm -Rf $(MYSQL_DUMPS_DIR)/*
	@rm -Rf web/app/vendor
	@rm -Rf web/app/composer.lock
	@rm -Rf web/app/doc
	@rm -Rf web/app/report
	@rm -Rf etc/ssl/*

composer-up:
	@docker run --rm -v $(shell pwd)/web/app:/app composer/composer update

docker-start:
	@echo "Docker is running..."
	docker-compose up -d

docker-stop:
	@docker-compose stop
	@docker-compose kill
	@docker-compose rm -f
	@make clean

gen-certs:
	@docker run --rm -v $(shell pwd)/etc/ssl:/certificates -e "SERVER=localhost" jacoelho/generate-certificate

logs:
	@docker-compose logs -f

mysql-dump:
	@mkdir -p $(MYSQL_DUMPS_DIR)
	@docker exec -i $(shell docker-compose ps -q mysqldb) mysqldump --all-databases -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" > $(MYSQL_DUMPS_DIR)/db.sql
	@make resetOwner

mysql-restore:
	@docker exec -i mysql mysql -u"$(MYSQL_ROOT_USER)" -p"$(MYSQL_ROOT_PASSWORD)" < $(MYSQL_DUMPS_DIR)/db.sql

test:
	@docker exec -i $(shell docker-compose ps -q php) app/vendor/bin/phpunit --colors=always --configuration app/
	@make resetOwner

resetOwner:
	@$(shell chown -Rf $(SUDO_USER):$(shell id -g -n $(SUDO_USER)) $(MYSQL_DUMPS_DIR) "$(shell pwd)/etc/ssl" "$(shell pwd)/web/app" 2> /dev/null)

.PHONY: clean