isProd := $(shell grep "APP_ENV=prod" .env.local > /dev/null && echo 1)
sy := php bin/console

make test-env:
	@echo $(isProd)

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

analyse: ##
	composer valid

start:
	@make docker-start
	@make composer-install
	@make db-restore-dev
	@make init-data

init-data:
	$(sy) app:versions
	$(sy) app:maps

# Docker
docker-start:
	@docker-compose --env-file ./.env.dev.local up -d

docker-stop:
	@docker-compose stop

docker-remove:
	@docker-compose rm -v database



#Command Interne
cmd-versions:
	 $(sy) app:versions


# Server
server-start:
	@symfony serve -d

server-list:
	@symfony server:list

server-stop:
	@symfony server:stop

# Doctrine
migrate:
	$(sy) make:migration

doc-migrate-dev:
	$(sy) doctrine:migration:migrate --env=dev -n

doc-migrate-test:
	$(sy) doctrine:migration:migrate --env=test -n

#Database
db-remove-dev:
	$(sy) doctrine:database:drop --force --env=dev --if-exists

db-create-dev:
	$(sy) doctrine:database:create --env=dev --if-not-exists

db-restore-dev:
	make db-remove-dev
	make db-create-dev
	make doc-migrate-dev

db-remove-test:
	$(sy) doctrine:database:drop --force --env=test --if-exists

db-create-test:
	$(sy) doctrine:database:create --env=test --if-not-exists

db-restore-test:
	make db-remove-test
	make db-create-test
	make doc-migrate-test

# Composer
composer-install:
	@composer install

install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	sed -i -e 's/ENV/$(env)/' .env.$(env).local
	composer install
	make prepare env=$(env)


test-start:
	@php bin/phpunit --coverage-html tests\coverage --coverage-text=tests\coverage.txt

cs-fix:
	vendor\bin\php-cs-fixer fix --allow-risky yes -vvv
