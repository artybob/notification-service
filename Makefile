.PHONY: up down build restart shell logs migrate fresh test phpstan pint

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

restart:
	docker-compose restart

shell:
	docker-compose exec php bash

logs:
	docker-compose logs -f

migrate:
	docker-compose exec php php artisan migrate

fresh:
	docker-compose exec php php artisan migrate:fresh --seed

test:
	docker-compose exec php php artisan test

setup: build up
	@echo "⏳ Waiting for database..."
	sleep 5
	docker-compose exec php composer install
	docker-compose exec php php artisan key:generate
	docker-compose exec php php artisan storage:link
	docker-compose exec php php artisan migrate
	@echo "✅ Project ready at http://localhost:8080"

phpstan:
	docker-compose exec php ./vendor/bin/phpstan analyse

pint:
	docker-compose exec php ./vendor/bin/pint

queue:
	docker-compose exec php php artisan queue:work

horizon:
	docker-compose exec php php artisan horizon

clean:
	docker-compose down -v
	rm -rf vendor
	rm -rf node_modules
	rm .env 2>/dev/null || true

dev-install:
	composer install
	cp .env.example .env 2>/dev/null || true
	php artisan key:generate
	php artisan migrate
