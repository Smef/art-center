#!/bin/bash

if [ ! -f /var/app/staging/storage/logs/laravel.log ]; then
    echo "Creating /storage/logs/laravel.log..."
    touch /var/app/staging/storage/logs/laravel.log
fi

echo "Making /storage writeable..."
chmod -R 775 /var/app/staging/storage
chmod -R 775 /var/app/staging/bootstrap/cache

chown -R webapp:webapp /var/app/staging/storage
chown -R webapp:webapp /var/app/staging/bootstrap/cache