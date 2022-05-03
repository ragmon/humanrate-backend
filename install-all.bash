#!/bin/bash

# Выполняет все шаги развертывания
./up.bash
./composer-install.bash
./migrate.bash


./run-tests.bash