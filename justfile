set dotenv-required

up:
    @docker compose up -d webserver

down:
    @docker compose down --remove-orphans

fresh:
    @just destroy
    @docker compose build --no-cache
    @docker compose pull
    @just setup
    @just db-fresh
    @just s3-bucket-fresh

destroy:
    @docker compose down --remove-orphans --volumes

setup:
    @docker compose run --rm cli composer install
    @just up
    @docker compose exec app sh -c 'grep -qE "^APP_KEY=.+$$" .env || php artisan key:generate'
    @docker compose exec app php artisan migrate
    @docker compose exec app php artisan db:seed
    @just s3-bucket
    @just clear-cache
    @docker compose run --rm node npm install
    @just assets

clear-cache:
    @docker compose exec app php artisan cache:clear
    @docker compose exec app php artisan clear-compiled
    @docker compose exec app php artisan config:clear
    @docker compose exec app php artisan route:clear
    @docker compose exec app php artisan view:clear

db-fresh:
    @docker compose exec app php artisan migrate:fresh
    @docker compose exec app php artisan db:seed

app:
    @docker compose exec app sh

cli:
    @docker compose run --rm --remove-orphans cli sh

schedule:
    @docker compose up schedule

assets:
    @docker compose run --rm --remove-orphans node npm run build

assets-dev:
    @docker compose run --rm --remove-orphans --publish 5173:5173 node npm run dev

pint:
    @docker compose run --rm --remove-orphans cli php ./vendor/bin/pint

pint-test:
    @docker compose run --rm --remove-orphans cli php ./vendor/bin/pint --test

pint-dirty:
    @docker compose run --rm --remove-orphans cli php ./vendor/bin/pint --dirty

phpstan:
    @docker compose run --rm --remove-orphans cli php ./vendor/bin/phpstan analyse --memory-limit 1G

s3-bucket:
    @docker compose run --rm --remove-orphans --entrypoint /bin/sh s3-cli -c " \
    mc alias set $MINIO_ALIAS $MINIO_ENDPOINT $MINIO_ACCESS_KEY $MINIO_SECRET_KEY; \
    mc mb --ignore-existing $MINIO_ALIAS/$MINIO_BUCKET; \
    mc anonymous set download $MINIO_ALIAS/$MINIO_BUCKET; \
    exit 0; \
    "

s3-bucket-fresh:
    @docker compose run --rm --remove-orphans --entrypoint /bin/sh s3-cli -c " \
    mc alias set $MINIO_ALIAS $MINIO_ENDPOINT $MINIO_ACCESS_KEY $MINIO_SECRET_KEY; \
    mc rb --force --dangerous $MINIO_ALIAS; \
    mc mb --ignore-existing $MINIO_ALIAS/$MINIO_BUCKET; \
    mc anonymous set download $MINIO_ALIAS/$MINIO_BUCKET; \
    exit 0; \
    "
