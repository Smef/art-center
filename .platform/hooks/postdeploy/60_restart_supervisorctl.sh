#!/bin/bash

# Restart Supervisor workers

echo "Rereading Supervisor configuration..."

supervisorctl reread
supervisorctl update

echo "Restarting Laravel queue..."
php /var/app/current/artisan queue:restart
echo "Starting Supervisor workers..."
sudo supervisorctl start "laravel-worker:*"
