#!/bin/bash

# Поднятие контейнеров среды
git submodule update --init --recursive
cp ./laradock.env ./laradock/.env
docker-compose -f ./laradock/docker-compose.yml up -d nginx mysql