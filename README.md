# Temper Retention Curves

Show the retention curves of the Temper onboarding process.

## Prerequisites

- Composer
- PHP 7.1
- MySQL with root access

If your MySQL has a root password, update `DB_PASSWORD` in the `.env` file that you get in the instructions below, since the current config file assumes no password.

## Installation

- `composer update`
- `cp .env.example .env`
- `php artisan key:generate`
- `mysql -uroot -p`
- `create database temper;` and then `exit` out
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve`
- Visit http://127.0.0.1:8000

## Testing

- `phpunit`