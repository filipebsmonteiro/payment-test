 docker-compose up -d
 docker-compose exec app bash
 cp .env.example .env
 composer install
 php artisan key:generate
 php artisan jwt:secret
 php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
 php artisan migrate
 php artisan db:seed
 php artisan optimize
 php vendor/bin/phpunit
 php artisan serve --host 0.0.0.0 --port 8000
