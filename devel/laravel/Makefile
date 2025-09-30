.PHONY: test serve autoload stan

test:
	php artisan test $(if $(TEST),--filter=$(TEST))

# Example usage: make unit TEST=SecurityTest
unit:
	php artisan test --testsuite=Unit $(if $(TEST),--filter=$(TEST))

# Example usage: make feature TEST=TradesImportTest
feature:
	php artisan test --testsuite=Feature $(if $(TEST),--filter=$(TEST))

serve:
	php artisan serve

# Example usage: make autoload, make dump, make reload
autoload dump reload:
	composer dump-autoload

stan:
	./vendor/bin/phpstan analyse --memory-limit=2G

