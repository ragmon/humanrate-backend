#!/bin/bash

# Поднятие контейнеров среды
cp ./src/.env.example ./src/.env
docker-compose -f ./laradock/docker-compose.yml exec -u laradock workspace composer install