# ============================================================
# K3 PLN — Shortcut commands
# Jalankan: make <command>
# ============================================================

.PHONY: setup fresh seed serve key

## Install composer & generate app key
setup:
	composer install
	cp -n .env.example .env
	php artisan key:generate
	@echo ""
	@echo "✅  Setup selesai. Edit .env lalu jalankan: make migrate"

## Migrasi database
migrate:
	php artisan migrate

## Seed semua data default
seed:
	php artisan db:seed

## Migrasi fresh + seed (HATI-HATI: hapus semua data!)
fresh:
	php artisan migrate:fresh --seed

## Jalankan dev server
serve:
	php artisan serve

## Clear semua cache
clear:
	php artisan config:clear
	php artisan cache:clear
	php artisan view:clear
	php artisan route:clear

## Optimize untuk production
optimize:
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
