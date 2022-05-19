# Human rating (API)

## Установка

```
git submodule update --init --recursive
cp laradock.env laradock/.env
docker-compose -f ./laradock/docker-compose.yml up -d nginx mysql
cp .env.example .env
cp ./src/.env.example ./src/.env
docker-compose -f ./laradock/docker-compose.yml exec -u laradock workspace composer install
docker-compose -f ./laradock/docker-compose.yml exec -u laradock workspace php artisan migrate:refresh
docker-compose -f ./laradock/docker-compose.yml exec -u laradock workspace php ./vendor/bin/phpunit
```

## Справка

* data/mysql - файлы данных БД Mysql

## Проблемы и решения с которыми столкнулся

### При попытке подключения к БД через DBeaver ошибка `MySQL : Public Key Retrieval is not allowed`:<br>
Решение: https://stackoverflow.com/a/50438872/2506123

## Полезные команды

### Засеить БД тестовыми данными
> docker-compose exec -u 1000 workspace php artisan db:seed

## Запустить тесты
> docker-compose exec -u 1000 workspace php ./vendor/bin/phpunit