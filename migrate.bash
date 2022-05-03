#!/bin/bash

# Поднятие контейнеров среды
docker-compose -f ./laradock/docker-compose.yml exec -u laradock workspace php artisan migrate:refresh