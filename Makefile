image:
	docker build -t integration-service-php-fpm -f ./docker/Dockerfile .

env:
	cp .env.example .env
	docker run --rm -v ${PWD}/.env:/var/www/html/.env --entrypoint php integration-service-php-fpm artisan key:generate

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

application:
	docker-compose up -d
	docker-compose exec app php artisan migrate
