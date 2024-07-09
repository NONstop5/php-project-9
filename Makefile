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

test:
	composer exec --verbose phpunit tests

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --color=always --coverage-clover build/logs/clover.xml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --color=always --coverage-text

test-coverage-html:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --color=always --coverage-html build/coverage

.PHONY: tests
