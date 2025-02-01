#!/bin/sh

# build the js assets
pnpm run build

#clear out the testing storage before starting anything, but don't delete .gitignore
echo "Clearing out testing storage..."
rm -rf storage/testing/*
#

# load the env directly into the shell because the artisan command doesn't seem to be able to read it with nested variables when running in a shell script
source .env.testing
# make a fake, blank .env to load since the env has already been loaded and we don't want to overwrite anything
touch .env.blank


echo "Clearing config and cache"
php artisan config:clear --env=blank
php artisan cache:clear --env=blank


## Run the migrations and seed the database with the TestingSeeder
# This ONE command does read the correct --env value, while the others all need blank
php artisan migrate:fresh --seed --seeder=TestingSeeder --env=testing

# After the migrations are run, we need to re-import the data into Algolia
# echo "Import resource data into Algolia"
# php artisan scout:import


# Start the server using the testing environment configuration so that we can run the test from a known state
echo "Starting testing web server..."
php artisan serve --env=blank --port=8001
if [ $? -eq 0 ]; then
    echo OK
else
    echo FAIL
    exit 1
fi
