install:
	composer install

PORT ?= 8000
start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

validate:
	composer validate

lint:
	composer exec -v phpcs public src tests
	vendor/bin/phpstan analyse

lint-fix:
	composer exec -v phpcbf -- --standard=PSR12 --colors public src tests

docker-up-d:
	docker-compose --env-file ./docker/.env up -d

docker-down:
	docker-compose --env-file ./docker/.env down -v

docker-build:
	docker-compose --env-file ./docker/.env build

test:
	composer exec --verbose phpunit tests

test-coverage-text:
	composer test:coverage-text

test-coverage-html:
	composer test:coverage-html

.PHONY: tests
