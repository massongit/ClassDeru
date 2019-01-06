#!/usr/bin/env bash

cd `dirname $0`
yarn install
composer install
cp ../laradock/env-example ../laradock/.env
