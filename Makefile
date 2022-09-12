#isProd := $(shell grep "APP_ENV=prod" .env.local > /dev/null && echo 1)
sy := php bin/console

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

make test-env:
	@echo $(isProd)


analyse: ## Analyse Composer Valid + PHPStan
	docker-compose exec server php bin/console doctrine:schema:valid --skip-sync
	php vendor/bin/phpstan analyse -c phpstan.neon --no-progress

start: ## Lance le container Docker + composer Install + DB Dev + init Data
	@make docker-start
	@make composer-install-dev
	@make db-restore-dev
	@make init-data

init-data: ## Initialise les datas (Version / Maps / Languages) en Dev
	docker-compose exec server php bin/console app:versions
	docker-compose exec server php bin/console app:maps
	docker-compose exec server php bin/console app:languages

init-data-prod: ## Initialise les datas (Version / Maps / Languages) en Prod
	docker-compose exec server php bin/console app:versions --env=prod
	docker-compose exec server php bin/console app:maps --env=prod
	docker-compose exec server php bin/console app:languages --env=prod


command-champion:
	@docker-compose exec server php bin/console app:champions

# Yarn

yarn-install: ## Yarn Install
	yarn install

yarn-dev: ## Yarn Dev
	yarn dev

yarn-watch: ## Yarn Watch
	yarn watch

# Docker
docker-start: ## Lance les container docker (Server et Database) avec .env.dev.local
	@docker-compose --env-file ./.env.dev.local up -d

docker-stop: ## Arrète les container Docker
	@docker-compose stop

docker-remove: ## Supprime les containers Docker
	@docker-compose rm -v database

# Server
server-start: ## Symfony - Lancer un server Local en Daemon
	@symfony serve -d

server-list: ## Symfony - Liste les servers local
	@symfony server:list

server-stop: ## Symfony - Stop le server local
	@symfony server:stop

# Doctrine
migrate: ## Génère la migration (in Docker)
	@docker-compose exec server php bin/console make:migration

doc-migrate-dev: ## Génère la migration (in Docker) en DEV
	@docker-compose exec server php bin/console doctrine:migration:migrate --env=dev -n

doc-migrate-test: ## Génère la migration (in Docker) en Test
	@docker-compose exec server php bin/console doctrine:migration:migrate --env=test -n

doc-migrate-prod: ## Génère la migration (in Docker) en PROD
	@docker-compose exec server php bin/console doctrine:migration:migrate --env=prod -n

#Database
db-remove-dev: ## Supprime la Base de donnée en DEV
	docker-compose exec server php bin/console doctrine:database:drop --force --env=dev --if-exists

db-create-dev: ## Créé la Base de donnée en DEV
	docker-compose exec server php bin/console doctrine:database:create --env=dev --if-not-exists

db-restore-dev: ## Supprime, re-crée et joue les migrations de la Base de donnée en DEV
	make db-remove-dev
	make db-create-dev
	make doc-migrate-dev


#Database
db-remove-prod: ## Supprime la Base de donnée en PROD
	docker-compose exec server php bin/console doctrine:database:drop --force --env=prod --if-exists

db-create-prod: ## Créé la Base de donnée en PROD
	docker-compose exec server php bin/console doctrine:database:create --env=prod --if-not-exists

db-restore-prod: ## Supprime, re-crée et joue les migrations de la Base de donnée en PROD
	make db-remove-prod
	make db-create-prod
	make doc-migrate-prod

db-remove-test: ## Supprime la Base de donnée en TEST
	docker-compose exec server php bin/console doctrine:database:drop --force --env=test --if-exists

db-create-test: ## Créé la Base de donnée en TEST
	docker-compose exec server php bin/console doctrine:database:create --env=test --if-not-exists

db-restore-test: ## Supprime, re-crée et joue les migrations de la Base de donnée en PROD
	make db-remove-test
	make db-create-test
	make doc-migrate-test

# Composer
composer-install-dev: ## Install les packages Composer en DEV
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

composer-install-prod:
	docker-compose exec server composer install --optimize-autoloader --no-dev


fixture-equipe: ## Generer les Equipes et Les compétitions
	docker-compose exec server php bin/console doctrine:fixtures:load --group=equipe --no-interaction


# Debug
debug-router:
	docker-compose exec server php bin/console debug:router

schemaspy: ## Générer un schema de la BDD avec SchemaSpy (dossier output)
	docker run --rm --network="host" -v %cd%/output:/output andrewjones/schemaspy-postgres:latest -host localhost -port 5432 -u toto -p toto -db app -s public