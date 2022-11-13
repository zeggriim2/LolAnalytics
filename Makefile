sy := symfony console
php := php bin/console
ENV?=dev
DIVISION?=IV
TIER=IRON

#--VARIABLES-----#
#---DOCKER---#
DOCKER= docker
DOCKER_RUN= ${DOCKER} run
DOCKER_COMPOSE= docker-compose
DOCKER_COMPOSE_UP= ${DOCKER_COMPOSE} up -d
DOCKER_COMPOSE_STOP= ${DOCKER_COMPOSE} stop
DOCKER_COMPOSE_EXEC= ${DOCKER_COMPOSE} exec
#------------#

#---COMPOSER---#
COMPOSER= composer
COMPOSER_INSTALL= ${COMPOSER} install
COMPOSER_UPDATE= ${COMPOSER} update
#------------#

#---NPM-----#
NPM = npm
NPM_INSTALL = $(NPM) install --force
NPM_UPDATE = $(NPM) update
NPM_BUILD = $(NPM) run build
NPM_DEV = $(NPM) run dev
NPM_WATCH = $(NPM) run watch
#------------#

#---YARN-----#
YARN = yarn
YARN_INSTALL = $(YARN) install --force
YARN_UPGRADE = $(YARN) upgrade
YARN_BUILD = $(YARN) build
YARN_DEV = $(YARN) dev
YARN_WATCH = $(YARN) watch
#------------#


## === 🆘  HELP ==================================================
help: ## Show this help.
	@echo "Symfon-Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#


## === ✨ APP ================================================
analyse: ## Analyse Composer Valid + PHPStan
	$(DOCKER_COMPOSE_EXEC) server php bin/console doctrine:schema:valid --skip-sync
	$(DOCKER_COMPOSE_EXEC) server php bin/console lint:twig templates/
	$(DOCKER_COMPOSE_EXEC) server php vendor/bin/phpstan analyse -c phpstan.neon --no-progress

cs-fix: ## CS Fixer
	vendor\bin\php-cs-fixer fix --allow-risky yes -vvv

init-data: ## Initialise les datas de Versions + Maps + Languages
	make data-versions
	make data-maps
	make data-languages
.PHONY:init-data

fixture-user: ## Fixture User
	$(DOCKER_COMPOSE_EXEC) server php bin/console doctrine:fixtures:load --no-interaction --group=user
.PHONY:fixture-user

schemaspy: ## Générer un schema de la BDD avec SchemaSpy (dossier output)
	docker run --rm --network="host" -v %cd%/output:/output andrewjones/schemaspy-postgres:latest -host localhost -port 5432 -u toto -p toto -db app -s public

start: ## Lance le container Docker + composer Install + DB Dev + init Data
	make docker-compose-env-up
	make composer-install
	#make db-restore
	#make fixture-user
.PHONY: start

#---------------------------------------------#
## === 📦  COMMANDE ================================================

cmd-init-data: ## Initialise les datas (Version / Maps / Languages) en Dev
	make cmd-maps
	make cmd-versions
	make cmd-languages

cmd-champions: ## Commande Champion
	$(DOCKER_COMPOSE_EXEC) server php bin/console app:champions
.PHONY: cmd-champions

cmd-languages: ## Commande Languages
	$(DOCKER_COMPOSE_EXEC) server php bin/console app:languages
.PHONY: cmd-languages

cmd-maps: ## Commande Maps
	$(DOCKER_COMPOSE_EXEC) server php bin/console app:maps
.PHONY: cmd-maps

cmd-versions: ## Commande Version
	$(DOCKER_COMPOSE_EXEC) server php bin/console app:version
.PHONY: cmd-versions

cmd-league: ## Commande League
	$(DOCKER_COMPOSE_EXEC) server php bin/console app:league $(DIVISION) $(TIER)
.PHONY: cmd-versions
#---------------------------------------------#

## === 📦  COMPOSER ================================================
composer-install: ## Install composer dependencies.
	$(COMPOSER_INSTALL)
.PHONY: composer-install

composer-update: ## Update composer dependencies.
	$(COMPOSER_UPDATE)
.PHONY: composer-update

composer-validate: ## Validate composer.json file.
	$(COMPOSER) validate
.PHONY:composer-validate

composer-validate-deep: ## Validate composer.json and composer.lock files in strict mode.
	$(COMPOSER) validate --strict --check-lock
.PHONY: composer-validate-deep

composer-install-prod:
	docker-compose exec server composer install --optimize-autoloader --no-dev
#---------------------------------------------#

## === ℹ  DATABASE ================================================
db-remove: ## Supprime la Base de donnée
	$(DOCKER_COMPOSE_EXEC) server php bin/console doctrine:database:drop --force --env=$(ENV) --if-exists
.PHONY: db-remove

db-create: ## Créé la Base de donnée
	$(DOCKER_COMPOSE_EXEC) server php bin/console doctrine:database:create --env=$(ENV) --if-not-exists
.PHONY: db-create

db-restore: ## Supprime, re-crée et joue les migrations de la Base de donnée en DEV
	make db-remove
	make db-create
	make sf-migrate
.PHONY: db-restore
#---------------------------------------------#

## === 🐋  DOCKER ================================================
docker-compose-up: ## Start docker containers.
	$(DOCKER_COMPOSE_UP)
.PHONY: docker-compose-up

docker-compose-env-up:
	$(DOCKER_COMPOSE) --env-file ./.env.$(ENV).local up -d
.PHONY: docker-compose-env-up

docker-compose-stop: ## Stop docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-compose-stop
#---------------------------------------------#

## === 🔎  TESTS =================================================
tests:
	$(DOCKER_COMPOSE_EXEC) server php bin/phpunit --coverage-html tests\coverage --coverage-text=tests\coverage.txt
.PHONY: tests
#---------------------------------------------#

## === 🎛️  SYMFONY ===============================================
sf-cc: ## Clear symfony cache(in Docker).
	$(DOCKER_COMPOSE_EXEC) server php bin/console c:c
.PHONY: sf-cc

sf-dmd: ## Migration Diff (in Docker)
	$(DOCKER_COMPOSE_EXEC) server php bin/console doctrine:migrations:diff
.PHONY: sf-dmd

sf-migrate: ## Génère la migration (in Docker)
	$(DOCKER_COMPOSE_EXEC) server php bin/console doctrine:migration:migrate --env=$(ENV) -n
.PHONY: sf-migrate

sf-mm: ## Make migration (in Docker)
	$(DOCKER_COMPOSE_EXEC) server php bin/console make:migration
.PHONY: sf-mm

sf-mc: ## Messenger Consume async(in Docker)
	$(DOCKER_COMPOSE_EXEC) server php bin/console messenger:consume async -vv
.PHONY: sf-mm


#---------------------------------------------#

## === 📦  YARN ===================================================
yarn-install: ## Install yarn dependencies.
	$(YARN_INSTALL)
.PHONY: yarn-install

yarn-upgrade: ## Update yarn dependencies.
	$(YARN_UPGRADE)
.PHONY: yarn-upgrade

yarn-build: ## Build assets.
	$(YARN_BUILD)
.PHONY: yarn-build

yarn-dev: ## Build assets in dev mode.
	$(YARN_DEV)
.PHONY: yarn-dev

yarn-watch: ## Watch assets.
	$(YARN_WATCH)
.PHONY: yarn-watch
#---------------------------------------------#

#help: ## Affiche cette aide
#	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install-env:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	sed -i -e 's/DATABASE_HOST/$(db_host)/' .env.$(env).local
	sed -i -e 's/DATABASE_NAME/$(db_name)/' .env.$(env).local
	sed -i -e 's/ENV/$(env)/' .env.$(env).local
	make prepare env=$(env)


#fixture-equipe: ## Generer les Equipes et Les compétitions
##	php bin/console doctrine:fixtures:load --group=equipe --no-interaction
#	docker-compose exec server php bin/console doctrine:fixtures:load --group=equipe --no-interaction
