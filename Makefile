image:
	docker build -t integration-service-php-fpm -f ./docker/Dockerfile .

env:
	cp .env.example .env
	docker run --rm -v ${PWD}/.env:/var/www/html/.env --entrypoint php integration-service-php-fpm artisan key:generate

install:
	docker run --rm -v ${PWD}:/app -w /app --user $(id -u):$(id -g) composer:2.2.7 install --ignore-platform-reqs

application:
	docker-compose up -d
	docker-compose exec app php artisan migrate

