#!/bin/bash
composer install

php artisan serve --host=0.0.0.0 --port=8000

RUN tail -f /dev/null