env:
	cp .env.example .env

install:
	docker run --rm -v ${PWD}:/app -w /app --user $(id -u):$(id -g) composer:2.2.7 install --ignore-platform-reqs

require:
	docker run --rm -v ${PWD}:/app -w /app --user $(id -u):$(id -g) composer:2.2.7 require $(filter-out $@,$(MAKECMDGOALS)) --ignore-platform-reqs

require-dev:
	docker run --rm -v ${PWD}:/app -w /app --user $(id -u):$(id -g) composer:2.2.7 require $(filter-out $@,$(MAKECMDGOALS)) --dev --ignore-platform-reqs

update:
	docker run --rm -v ${PWD}:/app -w /app --user $(id -u):$(id -g) composer:2.2.7 update $(filter-out $@,$(MAKECMDGOALS)) --ignore-platform-reqs

remove:
	docker run --rm -v ${PWD}:/app -w /app --user $(id -u):$(id -g) composer:2.2.7 remove $(filter-out $@,$(MAKECMDGOALS)) --ignore-platform-req=ext-mosquitto-php

up:
	docker-compose up -d

migrate:
	docker-compose exec app php artisan migrate
