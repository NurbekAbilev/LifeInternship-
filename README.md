# Ticket System
## Установка
1) `composer install`
1) (Windows) `copy .env.example .env` (Linux) `cp .env.example .env` 
1) (Windows) `copy .env.example .env.testing` (Linux) `cp .env.example .env.testing` 
1) Настроить параметры БД в `.env`
1) `php artisan migrate`
1) `php artisan key:generate`

## Модели
Модели находятся в директории `App\Models`.

## Seeding

Для того чтобы заполнить таблицу тестовыми данными:

`php artisan db:seed`





