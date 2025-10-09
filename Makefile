# Executables (local)
DOCKER_COMP = docker-compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

PHPQA_IMAGE = jakzal/phpqa:php8.4-alpine
DOCKER_RUN_PHPQA = docker run --rm -v $(PWD):/project -w /project $(PHPQA_IMAGE)

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the FrankenPHP container
	@$(PHP_CONT) sh

bash: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)


## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

## —— Jakzal 🎵 ———————————————————————————————————————————————————————————————
.PHONY: qa
qa: ## Run all quality tools (PHP CS Fixer + PHPStan)
	@echo "🔍 Running PHP CS Fixer..."
	$(MAKE) cs-check
	@echo "🔍 Running PHPStan..."
	$(MAKE) phpstan
	@echo "✅ All quality checks completed!"

.PHONY: cs-check
cs-check: ## Check coding standards (dry-run)
	@echo "🔍 Checking coding standards..."
	$(DOCKER_RUN_PHPQA) php-cs-fixer fix --dry-run --diff --verbose


.PHONY: cs-fix
cs-fix: ## Fix coding standards
	@echo "🔧 Fixing coding standards..."
	$(DOCKER_RUN_PHPQA) php-cs-fixer fix

.PHONY: phpstan
phpstan: ## Run PHPStan static analysis
	@echo "🔍 Running PHPStan analysis..."
	$(DOCKER_RUN_PHPQA) phpstan analyse --memory-limit=1G

.PHONY: phpstan-baseline
phpstan-baseline: ## Generate PHPStan baseline
	@echo "📝 Generating PHPStan baseline..."
	$(DOCKER_RUN_PHPQA) phpstan analyse --generate-baseline --memory-limit=1G

.PHONY: phpstan-clear
phpstan-clear: ## Clear PHPStan cache
	@echo "🗑️ Clearing PHPStan cache..."
	$(DOCKER_RUN_PHPQA) phpstan clear-result-cache
