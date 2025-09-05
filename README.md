# Laravel API Starter Template

Boilerplate for building APIs with Laravel, focused on productivity and best practices. Includes JWT authentication, roles/permissions, advanced pagination and filters, observability with Telescope, and Octane support. Can be run with Docker or locally.

## Setup

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan jwt:secret
touch database/database.sqlite
php artisan migrate
```

By default, the project uses SQLite. Ensure `DB_CONNECTION=sqlite` and `DB_DATABASE=./database/database.sqlite` in `.env`.

## Running with Docker

```bash
docker compose up -d --build
```

Access: [http://localhost](http://localhost)

## Running locally

```bash
php artisan serve
```

Access: [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Running with Octane

```bash
php artisan octane:install   # select FrankenPHP
php artisan octane:start --server=frankenphp --watch
```

Access: [http://127.0.0.1:8000](http://127.0.0.1:8000)

Note: `--watch` is recommended only for development.

## Documentation

* Postman collections: `docs/postman/`
* OpenAPI / Scalar docs: `docs/scalar/`

## Useful Routes

* API Documentation: [http://127.0.0.1:8000/docs/api](http://127.0.0.1:8000/docs/api)
* Health Check: [http://127.0.0.1:8000/api/health](http://127.0.0.1:8000/api/health)
* Application Logs (Telescope): [http://127.0.0.1:8000/telescope](http://127.0.0.1:8000/telescope)
