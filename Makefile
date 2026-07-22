.PHONY: up down build start stop restart logs shell artisan migrate seed fresh test whatsapp-qr deploy

up:
	docker compose up -d --build

down:
	docker compose down

build:
	docker compose build --no-cache

start:
	docker compose start

stop:
	docker compose stop

restart:
	docker compose restart

logs:
	docker compose logs -f

shell:
	docker compose exec app sh

artisan:
	docker compose exec app php artisan $(cmd)

migrate:
	docker compose exec app php artisan migrate --force

seed:
	docker compose exec app php artisan db:seed --force

fresh:
	docker compose exec app php artisan migrate:fresh --seed --force

deploy: up
	docker compose exec app php artisan migrate --force
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

whatsapp-qr:
	docker compose logs whatsapp 2>&1 | grep -oP 'data:image/png;base64[^"]*' | head -1

whatsapp-status:
	curl -s http://localhost:3000/status | python3 -m json.tool

test:
	docker compose exec app php artisan test

ps:
	docker compose ps
