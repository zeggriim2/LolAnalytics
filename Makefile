#---VARIABLES---------------------------------#
#---DOCKER---#
DOCKER = docker
DOCKER_RUN = $(DOCKER) run
DOCKER_COMPOSE = docker compose
#------------#

#---COMPOSER-#
COMPOSER = composer
COMPOSER_INSTALL = $(COMPOSER) install
COMPOSER_UPDATE = $(COMPOSER) update
#------------#


## === 🆘  HELP ==================================================
help: ## Show this help.
	@echo "Symfony-And-Docker-Makefile"
	@echo "---------------------------"
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
#---------------------------------------------#

## === 🐋  DOCKER ================================================
docker-start: ## Start Docker
	${DOCKER_COMPOSE} up -d
.PHONY: docker-start

docker-stop:
	@docker-compose stop
.PHONY: docker-stop

docker-remove:
	@docker-compose rm -v database
.PHONY: docker-remove
#---------------------------------------------#

## === 📦  COMPOSER ==============================================
composer-install: ## Install composer dependencies.
	$(COMPOSER_INSTALL)
.PHONY: composer-install

composer-update: ## Update composer dependencies.
	$(COMPOSER_UPDATE)
.PHONY: composer-update

composer-validate: ## Validate composer.json file.
	$(COMPOSER) validate
.PHONY: composer-validate

composer-validate-deep: ## Validate composer.json and composer.lock files in strict mode.
	$(COMPOSER) validate --strict --check-lock
.PHONY: composer-validate-deep
#---------------------------------------------#
cs-fix:
	vendor\bin\php-cs-fixer fix --allow-risky yes -vvv

# Server
server-start:
	@symfony serve -d

server-list:
	@symfony server:list

server-stop:
	@symfony server:stop


install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	sed -i -e 's/ENV/$(env)/' .env.$(env).local
	composer install
	make prepare env=$(env)

## === 🐛  TEST =================================================
test: ## Start PHPUNIT + Coverage
	@php bin/phpunit --coverage-html tests\coverage
.PHONY: test
#---------------------------------------------#