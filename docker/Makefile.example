.PHONY: up down build start stop restart logs shell artisan migrate seed fresh test deploy

up:
	docker compose --env-file ../.env up -d --build

down:
	docker compose --env-file ../.env down

build:
	docker compose --env-file ../.env build --no-cache

start:
	docker compose --env-file ../.env start

stop:
	docker compose --env-file ../.env stop

restart:
	docker compose --env-file ../.env restart

logs:
	docker compose --env-file ../.env logs -f

shell:
	docker compose --env-file ../.env exec app sh

artisan:
	docker compose --env-file ../.env exec app php artisan $(cmd)

migrate:
	docker compose --env-file ../.env exec app php artisan migrate --force

seed:
	docker compose --env-file ../.env exec app php artisan db:seed --force

fresh:
	docker compose --env-file ../.env exec app php artisan migrate:fresh --seed --force

deploy: up
	docker compose --env-file ../.env exec app php artisan migrate --force
	docker compose --env-file ../.env exec app php artisan config:cache
	docker compose --env-file ../.env exec app php artisan route:cache
	docker compose --env-file ../.env exec app php artisan view:cache


test:
	docker compose --env-file ../.env exec app php artisan test

ps:
	docker compose --env-file ../.env ps
