# Executables (local)
DOCKER_COMP = docker-compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php
NODE_CONT = $(DOCKER_COMP) exec node

PHPQA_IMAGE = jakzal/phpqa:php8.4-alpine
DOCKER_RUN_PHPQA = docker run --rm -v $(PWD):/project -w /project $(PHPQA_IMAGE)

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test test-unit test-functional

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

test-unit: ## Run unit tests only
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit tests/Unit

test-functional: ## Run functional tests only
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit tests/Functional

test-coverage: ## Run tests with code coverage (HTML report in var/coverage/)
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test -e XDEBUG_MODE=coverage php bin/phpunit --coverage-html var/coverage $(c)

test-coverage-text: ## Run tests with code coverage (text summary in terminal)
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test -e XDEBUG_MODE=coverage php bin/phpunit --coverage-text $(c)

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
qa: ## Run all quality tools (PHP + Front)
	@echo "🔍 Running PHP CS Fixer..."
	$(MAKE) cs-check
	@echo "🔍 Running PHPStan..."
	$(MAKE) phpstan
	@echo "🔍 Running ESLint..."
	$(MAKE) front-lint
	@echo "🔍 Running Prettier check..."
	$(MAKE) front-format-check
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

## —— Front Quality 🎨 —————————————————————————————————————————————————————————
.PHONY: front-lint
front-lint: ## Run ESLint on front assets
	@echo "🔍 Running ESLint..."
	@$(NODE_CONT) yarn lint

.PHONY: front-lint-fix
front-lint-fix: ## Fix ESLint errors on front assets
	@echo "🔧 Fixing ESLint errors..."
	@$(NODE_CONT) yarn lint:fix

.PHONY: front-format
front-format: ## Format front assets with Prettier
	@echo "🔧 Formatting with Prettier..."
	@$(NODE_CONT) yarn format

.PHONY: front-format-check
front-format-check: ## Check front assets formatting with Prettier
	@echo "🔍 Checking Prettier formatting..."
	@$(NODE_CONT) yarn format:check

.PHONY: front-type-check
front-type-check: ## Run TypeScript type checking
	@echo "🔍 Running TypeScript type check..."
	@$(NODE_CONT) yarn type-check

.PHONY: front-qa
front-qa: ## Run all front quality tools (ESLint + Prettier + TypeScript)
	@echo "🔍 Running ESLint..."
	$(MAKE) front-lint
	@echo "🔍 Checking Prettier formatting..."
	$(MAKE) front-format-check
	@echo "🔍 Running TypeScript type check..."
	$(MAKE) front-type-check
	@echo "✅ All front quality checks completed!"

## —— Node/Yarn 📦 ——————————————————————————————————————————————————————————————
.PHONY: yarn
yarn: ## Run yarn command, pass the parameter "c=" to run a given command, example: make yarn c="add vue"
	@$(eval c ?=)
	@$(NODE_CONT) yarn $(c)

.PHONY: yarn-install
yarn-install: ## Install node dependencies
	@$(NODE_CONT) yarn install

.PHONY: yarn-dev
yarn-dev: ## Start Vite dev server
	@$(NODE_CONT) yarn dev --host

.PHONY: yarn-build
yarn-build: ## Build assets for production
	@$(NODE_CONT) yarn build

.PHONY: node-sh
node-sh: ## Connect to the Node container
	@$(NODE_CONT) sh

.PHONY: node-logs
node-logs: ## Show Node container logs
	@$(DOCKER_COMP) logs --tail=50 --follow node
