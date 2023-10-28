#! /bin/bash
cd ./docker/

cp ./php-worker/supervisord.d/laravel-worker.conf.example ./php-worker/supervisord.d/laravel-worker.conf

docker compose up --build --remove-orphans -d
docker compose run workspace composer install
docker compose run workspace cp -n .env.example .env
docker compose run workspace php artisan migrate:fresh --seed
docker compose run workspace npm run build
