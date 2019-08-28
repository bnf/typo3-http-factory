.PHONY: check lint stan phpcs phpunit

check: lint phpcs stan phpunit

vendor/autoload.php: composer.json
	rm -rf composer.lock vendor/
	composer install

lint:
	find src/ -name '*.php' -exec php -l {} >/dev/null \;

stan: vendor/autoload.php
	vendor/bin/phpstan analyze

phpcs: vendor/autoload.php
	vendor/bin/phpcs

phpunit: vendor/autoload.php
	vendor/bin/phpunit
