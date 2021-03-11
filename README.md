<h1 align="center">Filipe Monteiro</h1>

<p align="center">
<a href="https://github.com/filipebsmonteiro">Github</a> |
<a href="https://www.linkedin.com/in/filipebsmonteiro/">Linkedin</a>
</p>

## Sobre o Projeto

Projeto baseado nos requisitos do teste de backend.

Pré requisitos para rodar o projeto:
- Docker
- Dockercompose

Comandos para executar após baixar o projeto:

- docker-compose up -d
- docker-compose exec app bash
- cp .env.example .env
- composer install
- php artisan key:generate
- php artisan jwt:secret
- php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
- php artisan migrate
- php artisan db:seed
- php artisan optimize
- php vendor/bin/phpunit
- php artisan serve --host 0.0.0.0 --port 8000

COMANDOS DISPONÍVEIS em um arquivo na RAIZ DO PROJETO: prepare-enviromnent.sh

## Endpoints
Todos os endpoints estão configurados em uma Collection do Postman, que pode ser importado

[Link da Collection do Postman](https://www.getpostman.com/collections/980e04208512c7098af1)
