#!/bin/bash

set APP_ENV = $(/opt/elasticbeanstalk/bin/get-config environment -k APP_ENV)
set LARAVEL_ENV_ENCRYPTION_KEY = $(/opt/elasticbeanstalk/bin/get-config environment -k LARAVEL_ENV_ENCRYPTION_KEY)

cd /var/app/staging

echo "Decrypting .env.$APP_ENV"
php artisan env:decrypt --env=$APP_ENV --key=$LARAVEL_ENV_ENCRYPTION_KEY

echo "Copying .env.$APP_ENV to .env"
cp ".env.$APP_ENV" .env
