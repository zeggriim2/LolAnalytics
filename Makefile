analyse:
	composer valid
	#docker-compose exec server php bin/console doctrine:schema:valid --skip-sync
	php vendor/bin/phpstan analyse -c phpstan.neon --no-progress

start:
	@make docker-start
	@make composer-install
	@make db-restore-dev
	@make init-data

init-data:
	docker-compose exec server php bin/console app:versions
	docker-compose exec server php bin/console app:maps
	docker-compose exec server php bin/console app:languages

init-data-prod:
	docker-compose exec server php bin/console app:versions --env=prod
	docker-compose exec server php bin/console app:maps --env=prod
	docker-compose exec server php bin/console app:languages --env=prod


command-champion:
	@docker-compose exec server php bin/console app:champions

# Docker
docker-start:
	@docker-compose --env-file ./.env.dev.local up -d

docker-stop:
	@docker-compose stop

docker-remove:
	@docker-compose rm -v database




# Server
server-start:
	@symfony serve -d

server-list:
	@symfony server:list

server-stop:
	@symfony server:stop

# Doctrine
migrate:
	@docker-compose exec server php bin/console make:migration

doc-migrate-dev:
	@docker-compose exec server php bin/console doctrine:migration:migrate --env=dev -n

doc-migrate-test:
	@docker-compose exec server php bin/console doctrine:migration:migrate --env=test -n

doc-migrate-prod:
	@docker-compose exec server php bin/console doctrine:migration:migrate --env=prod -n

#Database
db-remove-dev:
	docker-compose exec server php bin/console doctrine:database:drop --force --env=dev --if-exists

db-create-dev:
	docker-compose exec server php bin/console doctrine:database:create --env=dev --if-not-exists

db-restore-dev:
	make db-remove-dev
	make db-create-dev
	make doc-migrate-dev


#Database
db-remove-prod:
	docker-compose exec server php bin/console doctrine:database:drop --force --env=prod --if-exists

db-create-prod:
	docker-compose exec server php bin/console doctrine:database:create --env=prod --if-not-exists

db-restore-prod:
	make db-remove-prod
	make db-create-prod
	make doc-migrate-prod

db-remove-test:
	docker-compose exec server php bin/console doctrine:database:drop --force --env=test --if-exists

db-create-test:
	docker-compose exec server php bin/console doctrine:database:create --env=test --if-not-exists

db-restore-test:
	make db-remove-test
	make db-create-test
	make doc-migrate-test

# Composer
composer-install-dev:
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