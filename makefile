APP_PORT := $(shell grep '^APP_PORT=' .env | cut -d '=' -f2)
APP_NAME := $(shell grep '^APP_NAME=' .env | cut -d '=' -f2)

.PHONY: artisan

define print-info
	@echo ""
	@echo "========================================"
	@echo "🚀 $(APP_NAME) started"
	@echo "🌐 http://localhost:$(APP_PORT)"
	@echo "========================================"
endef
up:
	docker-compose up -d
	$(call print-info)

down:
	docker-compose down

restart:
	docker-compose down
	docker-compose up -d
	$(call print-info)

build:
	docker-compose build --no-cache

rebuild:
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d
	$(call print-info)

php:
	docker-compose exec php bash

artisan:
	docker-compose exec -u appuser php php artisan $(name)

model:
	docker-compose exec -u appuser php php artisan make:model $(name) -m

migrate:
	docker-compose exec php php artisan migrate

migration:
	docker-compose exec -u appuser php php artisan make:migration $(name)

command:
	docker-compose exec -u appuser php php artisan make:command

test:
	docker-compose exec php php artisan test

migrate-fresh:
	docker-compose exec php php artisan migrate:fresh

migrate-seed:
	docker-compose exec php php artisan migrate --seed

rollback:
	docker-compose exec php php artisan migrate:rollback --step=1

cache:
	docker-compose exec php php artisan optimize:clear

composer:
	docker-compose exec php composer install

npm-install:
	docker-compose exec php npm install

npm-dev:
	docker-compose exec php npm run dev

npm-watch:
	docker-compose exec php npm run watch

npm-build:
	docker-compose exec php npm run build

logs:
	docker-compose logs -f

ps:
	docker-compose ps
