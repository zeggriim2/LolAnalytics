#---VARIABLES---------------------------------#
#---DOCKER---#
DOCKER = docker
DOCKER_RUN = $(DOCKER) run
DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_UP = $(DOCKER_COMPOSE) --env-file ./.env.dev.local up --build -d
DOCKER_COMPOSE_STOP = $(DOCKER_COMPOSE) stop
DOCKER_COMPOSE_DOWN = $(DOCKER_COMPOSE) down
#------------#

#---SYMFONY---#
SYMFONY = symfony
SYMFONY_SERVER_START = $(SYMFONY) serve -d
SYMFONY_SERVER_STOP = $(SYMFONY) server:stop
SYMFONY_CHECK = $(SYMFONY) security:check
SYMFONY_CONSOLE = $(SYMFONY) console
SYMFONY_LINT = $(SYMFONY_CONSOLE) lint:
#------------#

#---PHP---#
PHP = php
PHP_BIN= $(PHP) bin/console
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
## ===  Initialisation ================================================
install: ## Installation de l'application
	make docker-up
	composer install
	make sf-reset-db
	make init-all
.PHONY: install

init-all: ## Initialise toutes les données static (Versions, Maps, Queues, Seasons, GameModes, GameTypes, Languages)
	make cmd-versions
	make cmd-maps
	make cmd-queues
	make cmd-seasons
	make cmd-gamemodes
	make cmd-gametypes
	make cmd-languages
.PHONY: init-all

cmd-versions: ## Command Versions.
	$(SYMFONY_CONSOLE) app:versions
.PHONY: cmd-versions

cmd-maps: ## Command Maps.
	$(SYMFONY_CONSOLE) app:maps
.PHONY: cmd-maps

cmd-queues: ## Command Queues.
	$(SYMFONY_CONSOLE) app:queues
.PHONY: cmd-queues

cmd-seasons: ## Command Seasons.
	$(SYMFONY_CONSOLE) app:seasons
.PHONY: cmd-seasons

cmd-gamemodes: ## Command Game Mode.
	$(SYMFONY_CONSOLE) app:gamemodes
.PHONY: cmd-gamemodes

cmd-gametypes: ## Command Game Type.
	$(SYMFONY_CONSOLE) app:gametypes
.PHONY: cmd-gametypes

cmd-languages: ## Command Languages.
	$(SYMFONY_CONSOLE) app:languages
.PHONY: cmd-languages

#---------------------------------------------#

## === 🐋  DOCKER ================================================
docker-up: ## Start docker containers.
	$(DOCKER_COMPOSE_UP)
.PHONY: docker-up

docker-stop: ## Stop docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-stop

docker-down: ## Down docker containers.
	$(DOCKER_COMPOSE_STOP)
.PHONY: docker-stop
#---------------------------------------------#


## === 🎛️  SYMFONY ===============================================
sf-dc: ## Create symfony database.
	$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists
.PHONY: sf-dc

sf-dd: ## Drop symfony database.
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force
.PHONY: sf-dd

sf-mm: ## Make migrations.
	$(SYMFONY_CONSOLE) make:migration
.PHONY: sf-mm

sf-dmm: ## Migrate.
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction
.PHONY: sf-dmm

sf-fixtures: ## Load fixtures.
	$(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction
.PHONY: sf-fixtures

sf-reset-db: ## Reset toute la base de donnée
	make sf-dd
	make sf-dc
	make sf-dmm
.PHONY: sf-reset-db

#---------------------------------------------#