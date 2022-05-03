# Human rating (API)

## Установка (автоматическая)
1. Выполнить скрипт
```
./install-all.bash
```

## Установка (ручная)

1. Установить git submodule
```
git submodule update --init --recursive
cp laradock.env laradock/.env
```
2. Развернуть docker среду (среда будет собираться долго. нужно набраться терпения)
```
cd laradock
cp .env.example .env
sudo ./up.bash

# установить зависимости
sudo ./composer-install.bash
# развернуть БД миграции  
sudo ./migrate.bash
```
3. Перейти на http://localhost и увидеть `Lumen (9.0.2) (Laravel Components ^9.0)`

## Использование

1. Перейти http://localhost/ или http://127.0.0.1/ <br>
    Должно показать сообщение `Lumen (9.0.2) (Laravel Components ^9.0)`. 
   Значит проект успешно развернут.
2. Можно отправлять HTTP запросы на endpoint: http://localhost/api

## Справка

* data/mysql - файлы данных БД Mysql

## Проблемы и решения с которыми столкнулся

### При попытке подключения к БД через DBeaver ошибка `MySQL : Public Key Retrieval is not allowed`:<br>
Решение: https://stackoverflow.com/a/50438872/2506123

## Полезные команды

### Засеить БД тестовыми данными
> docker-compose exec -u 1000 app php artisan db:seed

## Запустить тесты
> docker-compose exec -u 1000 app php vendor/bin/phpunit