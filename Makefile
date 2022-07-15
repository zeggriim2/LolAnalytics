install:
	@composer install

analyse:
	composer valid

cs-fix:
	vendor\bin\php-cs-fixer fix --allow-risky yes -vvv

# Docker
docker-start:
	@docker-compose up -d

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


install:
	cp .env.dist .env.$(env).local
	sed -i -e 's/DATABASE_USER/$(db_user)/' .env.$(env).local
	sed -i -e 's/DATABASE_PASSWORD/$(db_password)/' .env.$(env).local
	sed -i -e 's/ENV/$(env)/' .env.$(env).local
	composer install
	make prepare env=$(env)


test-start:
	@php bin/phpunit --coverage-html tests\coverage --coverage-text=tests\coverage.txt