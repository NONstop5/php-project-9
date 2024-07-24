install:
	composer install

PORT ?= 8000
start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

validate:
	composer validate

dump:
	composer dump-autoload -o

lint:
	composer exec -v phpcs src tests

lint-fix:
	composer exec -v phpcbf -- --standard=PSR12 --colors src tests

phpstan:
	vendor/bin/phpstan analyse

docker-up-d:
	docker-compose up -d

compose-bash:
	docker-compose run web bash

compose-setup: docker-build
	docker-compose run web make setup

docker-build:
	docker-compose build

docker-down:
	docker-compose down -v
