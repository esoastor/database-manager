#!/usr/bin/env bash

if [[ -d vendor ]]
then
    ./vendor/bin/phpunit
else 
    composer update
    composer test
fi
