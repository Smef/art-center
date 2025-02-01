# Gearbox Phase 1 Starter Kit

This app was developed by [Gearbox Solutions](https://gearboxgo.com/).

-   [Dev site](https://braavos-dev.finishedart.com)
-   production site (coming soon)

## Frameworks, Packages, and Tools

This solutions is built on the Laravel framework. It uses Laravel Jetstream as a starting point for building out the app.

-   [PHP 8.2](https://www.php.net/)
-   [Node 18](https://nodejs.org/en/)
-   [PNPM](https://pnpm.io/installation) - Node Package manager
-   [Composer](https://getcomposer.org/) - PHP package manager
-   [Laravel](https://laravel.com/) - PHP Framework
-   [Laravel Jetstream](https://jetstream.laravel.com/2.x/introduction.html) - Laravel app starter-kit
-   [Inertia](https://inertiajs.com/) - Connects back-end to Vue front-end
-   [Vue 3](https://vuejs.org/) - Front-end JavaScript framework
-   [Tailwind CSS](https://tailwindcss.com/) - Front-end styling
-   [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v5/introduction) - Role and permission management
-   [Clockwork](https://github.com/itsgoingd/clockwork) - Dev tools for performance and app analysis
-

## Services used in production

-   AWS Elastic Beanstalk
-   AWS CodePipeline
-   AWS CodeBuild
-   [Sentry](https://sentry.io/)

## Local Development

-   Check out project from GitHub to local development environment
-   Copy `.env.local` to `.env` and fill in the missing entries or update existing entries
-   Generate an `APP_KEY` entry using `php artisan key:generate`
-   Run `php artisan migrate` to set up the database
    -   If you get an error trying to migrate, you may need to make sure that you have the `mysql` command installed and linked to your shell.
    -   If you are using MAMP you can link the internal mysql command with `ln -s /Applications/MAMP/Library/bin/mysql /usr/local/bin/mysql`
    -   If you are using Homebrew you can install mysql with `brew install mysql` or `brew install mysql-client` (This only installs the client, not the DB engine)
-   Run `php artisan db:seed` to seed the database with test data
-   Install PHP packages with `composer install`
-   If you have `nvm`, use `nvm use` to make sure you have the correct version of Node installed
-   Install JavaScript dependencies with `pnpm install`
-   Compile JavaScript with `pnpm run dev`
-   Set up a web host with `public` folder as root
-   Open your host in your local server and enjoy!

### Signing in to your local app

For a new project, the easiest way to start is to add yourself as an admin user in the `/database/seeds/DatabaseSeeder.php` seeder before you run it (or you can re-run with `artisan migrate:fresh --seed`)

If you can't re-run the seeder, you can add yourself as an admin user by following these steps:

1. Register an account at https://yourdomain.test/register
2. Add yourself to the admin role by adding an entry in the `model_has_roles` table for your user id and the admin role id.

Example:

If your users is User ID 51 and the 'Admin' role is Role ID 2, you would add the following entry to the `model_has_roles` table:
```
model_type: App\Models\User
model_id: 51
role_id: 2
```


## Deploying to Production

This app is built and deployed to production automatically using a CI/CD pipeline when changes are committed to the `main` branch.

When changes are committed on the `main` branch, GitHub fires a webhook to Amazon CodePipeline. This webhook starts a pipeline which gets the code from GitHub, runs a build process through CodeBuild, and then deploys to the production environment on Elastic Beanstalk.

### Production Environment Variables

The production environment variables are managed through an encrypted `.env.production.encrypted` file which is safe to check into version control. The `.env.production.encrypted` file is decrypted during the build and deploy processes and used to set the environment variables in the production environment.

You can update the production environment variables by updating the `.env.production.encrypted` file and committing the changes to version control. To decrypt the file and make changes you will need to do the following:

1. Prepare the decrypt/encrypt shell scripts
    - Copy the encryption/decryption scripts from `env-decrypt.sh.dist` and `env-encrypt.sh.dist` to `decrypt-env.sh` and `encrypt-env.sh` respectively
    - Update the {ENCRYPTION_KEY} in the scripts with the encryption key ( which should be stored in 1Password )
    - Check that the script is executable with `chmod +x decrypt-env.sh`


2. Make the changes to the environment variables
    - Decrypt the `.env.production.encrypted` file using `./decrypt-env.sh`
    - Make changes to the `.env.production` file as needed
    - Re-encrypt the `.env.production` file using `./encrypt-env.sh`
    - Commit the changes to the `.env.production.encrypted` file to version control

## Build Process

The build process in CodeBuild is configured using `buildspec.yml`.

The Elastic Beanstalk environment, including Nginx server configuration is controlled through the `.platform` directory

## Favicons, PWA colors, and app icon configuration

* Update Icons in
    * `/public/favicons` (Generate an icon from https://realfavicongenerator.net/)
    * `/resources/js/Compnents/BrandIcon.vue` and `BrandLogoFull.vue`
* Update colors
    * PWA frame/loading color: `/public/manifest.json`
    * PWA theme color: `<meta name="theme-color"` in `/resources/views/app.blade.php`
    * Button and highlight colors: Any necessary colors, along with `brand-primary` in `tailwind.config.js` (https://www.tailwindshades.com/)
    * Progress color: `progress.color` in `app.ts`
* Update PWA Info
    * description and name in `/public/manifest.json`
    * `id` in `public/manifest.json`

## Sentry Error Monitoring

This app is configured to send errors and source maps to Sentry. To enable Sentry error capture, add the Sentry DSN to the `.env.production` file as `SENTRY_DSN`. This will allow sending both Laravel and Vue errors.

Source maps can also be uploaded to sentry during the build process which helps with diagnosing issues in production. To do this, you will need to create an auth token and add it to the `.env.production` file under `SENTRY_AUTH_TOKEN`. You can create an auth token [here](https://gearbox.sentry.io/settings/auth-tokens/).

## Performance and app analysis with Clockwork dev tools

[Clockwork on Github](https://github.com/itsgoingd/clockwork)

This app is configured to use Clockwork for performance and app analysis in your local development environment. Clockwork provides insight into app behaviors such as viewing SQL queries, app performance, and more. There are two ways to view this information:

1. (Preferred) Install the [Chrome extension](https://chromewebstore.google.com/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp) and view the Clockwork tab in the Chrome dev tools
2. View the Clockwork dashboard at https://yourapp.test/clockwork/app

# Automated Testing

Tests are run using PHPUnit and [Laravel's native testing features](https://laravel.com/docs/11.x/testing) and [Playwright](https://playwright.dev/). PHPUnit is used for unit and feature tests, while Playwright is used for end-to-end tests.

## Setting up the Testing Environment

### .env.testing
Tests are expected to be run using `.env.testing` as the environment. This environment file is automatically used by the testing suite when running tests using the run options described below. The environment config file should be decrypted from `.env.testing.encrypted` to `.env.testing` by running:
```bash
php artisan env:decrypt --env=testing --key=xxxxxx --force
```


Testing requires the application to be in a specific, known state to ensure that tests are consistent and produce the same results every time the tests are run. Because of this, tests should be run on a separate database and filesystem from the developer's local environment. The sections below describe how the testing environment is configured for the database, file storage, and web server.


### Database
This environment is configured to look for a separate testing database named `phase_1_kit_testing` by default. This database should be manually created before running tests, and connection information should be configured in `.env.testing` after it has been decrypted. A separate database is used so that it can be wiped and set to a known state before tests are run. This is particularly important for the end-to-end testing, which requires known application state.

### File Storage

The test suite is configured to run with a separate local `testing` filesystem, configured in `filesystem.php`. The `testing` filesystem is similar to the regular Laravel `local` filesystem driver, but it stores its files in `storage/testing` instead of `storage/app`. This separate testing filesystem is to ensure that test file uploads can be wiped and reset regularly without affecting the developer's testing environment. Files in this directory are wiped before end-to-end tests are run.

### Web Server

Before running End-to-End tests, Playwright will launch a local server to run the tests against. This server will be launched using the `php artisan serve` command and use the `.env.testing` file to configure the server. This server will be launched on port 8001 by default and will shut down when the tests end.

## Running Tests

### Unit & Feature
Unit testing is done using PHPUnit and [Laravel's native testing features](https://laravel.com/docs/11.x/testing). To run the tests, use the following commands:

#### Reset and seed the testing database
```bash
php artisan migrate:fresh --seed --seeder=TestingSeeder --env=testing
```

#### Run all tests
```bash
php artisan test --stop-on-failure
```

#### Run a single test
```bash
php artisan test --stop-on-failure --filter EquationAnswerTest
```

### End-to-End (Playwright)

You will need to make sure that Playwright's browsers are installed before running tests. You can do this by running the following command:
```bash
npx playwright install
```

There are lots of different ways to run and view test progress. Check out the [Playwright documentation on running tests](https://playwright.dev/docs/running-tests) for more information. Here are some common commands for running tests:

#### Open the test UI
```bash
playwright test --ui
```

#### Start recording a new test with Codegen
```bash
playwright codegen https://phase-1.test/login
```
#### Run all tests (headless)
```bash
playwright test
```

#### Run all tests (UI)
```bash
playwright test --ui
```
