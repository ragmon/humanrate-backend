#!/bin/bash

docker-compose -f ./laradock/docker-compose.yml exec -u laradock workspace php ./vendor/bin/phpunit