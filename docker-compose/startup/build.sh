#!/bin/bash

composer install --no-interaction
php artisan migrate --force

exec "$@"