analyse:
	composer valid

app:
    @php bin/console app:maps
    @php bin/console app:versions

# Docker
docker-start:
	@docker-compose up -d

docker-stop:
	@docker-compose stop

docker-remove:
	@docker-compose rm -v database



#Command Interne
cmd-versions:
	 @php bin/console app:versions


# Server
server-start:
	@symfony serve -d

server-list:
	@symfony server:list

server-stop:
	@symfony server:stop

# Doctrine
migrate:
	@php bin/console make:migration

doc-migrate-dev:
	@php bin/console doctrine:migration:migrate --env=dev -n

doc-migrate-test:
	@php bin/console doctrine:migration:migrate --env=test -n

#Database
db-remove-dev:
	php bin/console doctrine:database:drop --force --env=dev --if-exists

db-create-dev:
	php bin/console doctrine:database:create --env=dev --if-not-exists

db-restore-dev:
	make db-remove-dev
	make db-create-dev
	make doc-migrate-dev

db-remove-test:
	php bin/console doctrine:database:drop --force --env=test --if-exists

db-create-test:
	php bin/console doctrine:database:create --env=test --if-not-exists

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
