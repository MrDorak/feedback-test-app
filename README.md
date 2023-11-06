<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


### Setup
https://laravel.com/docs/10.x/installation#laravel-and-docker
To setup the app and run it locally please follow these steps
- `docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
laravelsail/php82-composer:latest \
composer install --ignore-platform-reqs`

- `./vendor/bin/sail up -d`

- `./vendor/bin/sail migrate`

- `./vendor/bin/sail yarn install`

- `./vendor/bin/sail yarn dev`

### Getting started

Importing feedback through the example CSV file and sending the mail with the attached json of the imported data to the admin user: (no time constraint in local env)
- `./vendor/bin/sail artisan schedule:run`

The email sent should then appear on `localhost:1025`.

Access `localhost` to create a simple feedback entry in the application.

Access `localhost/login` and log in to access the dashboard (`localhost/dashboard`) and import the CSV file in the application.

