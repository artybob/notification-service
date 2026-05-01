.PHONY: up down build restart shell logs migrate fresh test clean setup phpstan pint

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

restart:
	docker compose restart

shell:
	docker compose exec php bash

logs:
	docker compose logs -f

migrate:
	docker compose exec php php artisan migrate

fresh:
	docker compose exec php php artisan migrate:fresh --seed

test:
	docker compose exec php php artisan test

clean:
	docker compose down -v
	sudo rm -rf vendor composer.lock
	sudo rm -rf storage/framework/cache/* storage/framework/sessions/* storage/framework/views/* 2>/dev/null || true

setup: build up
	@echo "⏳ Waiting for database..."
	sleep 5
	@echo "📝 Creating .env file if not exists..."
	test -f .env || cp .env.example .env
	@echo "📦 Installing composer dependencies..."
	docker compose exec --user root php composer install --prefer-dist --no-interaction
	@echo "🔑 Generating APP_KEY..."
	docker compose exec php php artisan key:generate --force
	@echo "🗄️ Running migrations..."
	docker compose exec php php artisan migrate --force
	@echo "✅ Project ready at http://localhost:8080"

phpstan:
	docker compose exec php ./vendor/bin/phpstan analyse

pint:
	docker compose exec php ./vendor/bin/pint
