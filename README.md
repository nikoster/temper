# Temper PHP Assessment

Show the retention curves of the Temper onboarding process.

## Installation

- `composer update`
- `cp .env.example .env`
- `php artisan key:generate`
- `mysql -uroot -p` and hit enter on password input
- `create database temper;` and then `exit` out
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve`
- Visit http://127.0.0.1:8000

## Testing

- `phpunit`