.PHONY: test serve autoload stan

test:
	php artisan test $(if $(TEST),--filter=$(TEST))

# Example usage: make unit TEST=SecurityTest
unit:
	php artisan test --testsuite=Unit $(if $(TEST),--filter=$(TEST))

# Example usage: make feature TEST=TradesImportTest
feature:
	php artisan test --testsuite=Api $(if $(TEST),--filter=$(TEST))
	php artisan test --testsuite=Integration $(if $(TEST),--filter=$(TEST))

# Example usage: make api TEST=TradesImportTest
api:
	php artisan test --testsuite=Api $(if $(TEST),--filter=$(TEST))

# Example usage: make integration TEST=TradesImportTest
integration:
	php artisan test --testsuite=Integration $(if $(TEST),--filter=$(TEST))

serve:
	php artisan serve

# Example usage: make autoload, make dump, make reload
autoload dump reload:
	composer dump-autoload

stan:
	./vendor/bin/phpstan analyse --memory-limit=2G

