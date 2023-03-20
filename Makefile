export COMPOSE_PROJECT_NAME=figma-backup
export WEB_PORT_HTTP=80
export INNODB_USE_NATIVE_AIO=1


MAKEFLAGS += --no-print-directory

# determine if .env file exist
ifneq ("$(wildcard .env)","")
	include .env
endif

ifndef INSIDE_DOCKER_CONTAINER
	INSIDE_DOCKER_CONTAINER = 0
endif

HOST_UID := $(shell id -u)
HOST_GID := $(shell id -g)
PHP_USER := -u www-data
PROJECT_NAME := -p ${COMPOSE_PROJECT_NAME}
INTERACTIVE := $(shell [ -t 0 ] && echo 1)
ERROR_ONLY_FOR_HOST = @printf "\033[33mThis command for host machine\033[39m\n"
.DEFAULT_GOAL := info

ifneq ($(INTERACTIVE), 1)
	OPTION_T := -T
endif



## build environment
build:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose -f docker-compose.yml build
else
	$(ERROR_ONLY_FOR_HOST)
endif



## start environment
start:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose -f docker-compose.yml $(PROJECT_NAME) up -d
else
	$(ERROR_ONLY_FOR_HOST)
endif



## stop environment
stop:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose -f docker-compose.yml $(PROJECT_NAME) down
else
	$(ERROR_ONLY_FOR_HOST)
endif



## stop and start dev environment
restart: stop start



## creates config for production environment
env:
	@make exec cmd="cp ./.env.example ./.env"



## get bash inside backend docker container
ssh:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) backend bash
else
	$(ERROR_ONLY_FOR_HOST)
endif



## get bash inside nginx docker container
ssh-nginx:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose $(PROJECT_NAME) exec nginx /bin/sh
else
	$(ERROR_ONLY_FOR_HOST)
endif




## get bash inside supervisord docker container (cron jobs running there, etc...)
ssh-supervisord:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose $(PROJECT_NAME) exec supervisord bash
else
	$(ERROR_ONLY_FOR_HOST)
endif



## get bash inside mysql docker container
ssh-mysql:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose $(PROJECT_NAME) exec mysql bash
else
	$(ERROR_ONLY_FOR_HOST)
endif



# exec cmd
exec:
ifeq ($(INSIDE_DOCKER_CONTAINER), 1)
	@$$cmd
else
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) backend $$cmd
endif



# exec bash commands
exec-bash:
ifeq ($(INSIDE_DOCKER_CONTAINER), 1)
	@bash -c "$(cmd)"
else
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker-compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) backend bash -c "$(cmd)"
endif




# download figma backup files
figma-backup:
	@make exec cmd="php artisan figma:backup"



# delete old backup files
figma-delete-old-files:
	@make exec cmd="php artisan figma:delete-old-files"



# make admin
make-admin:
	@make exec cmd="php artisan make:admin"




# wait for database
wait-for-db:
	@make exec cmd="php artisan db:wait"



## installs composer no-dev dependencies
composer-install-no-dev:
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader --no-dev"



## installs composer dependencies
composer-install:
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader"



## updates composer dependencies
composer-update:
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer update"



## sets the application key
key-generate:
	@make exec cmd="php artisan key:generate"



## install npm dependencies
npm-install:
	@make exec cmd="npm i"



## update npm dependencies
npm-update:
	@make exec cmd="npm update"



## build assets
npm-build:
	@make exec cmd="npm run build"



## remove node_modules
remove-node-modules:
	@make exec cmd="rm -rf node_modules"



## install dependencies
install-dependencies: composer-install npm-install npm-build remove-node-modules



## shows Php and Laravel version
info:
	@make exec cmd="php artisan --version"
	@make exec cmd="php artisan env"
	@make exec cmd="php --version"



## shows logs from the backend container. Use ctrl+c in order to exit
logs:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-backend
else
	$(ERROR_ONLY_FOR_HOST)
endif



## shows logs from the nginx container. Use ctrl+c in order to exit
logs-nginx:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-nginx
else
	$(ERROR_ONLY_FOR_HOST)
endif



## shows logs from the supervisord container. Use ctrl+c in order to exit
logs-supervisord:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-supervisord
else
	$(ERROR_ONLY_FOR_HOST)
endif



## shows logs from the mysql container. Use ctrl+c in order to exit
logs-mysql:
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@docker logs -f ${COMPOSE_PROJECT_NAME}-mysql
else
	$(ERROR_ONLY_FOR_HOST)
endif



## drops databases and runs all migrations for the main/test databases
drop-migrate:
	@make exec cmd="php artisan migrate:fresh"
	@make exec cmd="php artisan migrate:fresh --env=test"



## runs all migrations for main database
migrate-no-test:
	@make exec cmd="php artisan migrate --force"



## runs all migrations for main/test databases
migrate:
	@make exec cmd="php artisan migrate --force"
	@make exec cmd="php artisan migrate --force --env=test"



## runs all seeds for test database
seed:
	@make exec cmd="php artisan db:seed --force"



## normalizes composer.json file content
composer-normalize:
	@make exec cmd="composer normalize"



## validates composer.json file content
composer-validate:
	@make exec cmd="composer validate --no-check-version"
