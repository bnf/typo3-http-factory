.PHONY: check lint stan

check: lint phpcs stan

vendor/autoload.php: composer.json
	rm -rf composer.lock vendor/
	composer install

lint:
	find src/ -name '*.php' -exec php -l {} >/dev/null \;

stan: vendor/autoload.php
	vendor/bin/phpstan analyze

phpcs: vendor/autoload.php
	vendor/bin/phpcs
