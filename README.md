
## Pinboard App Test Project

## Commands to run for serving the application

- composer install
- npm install && npm run dev

## Migrate the database

- Copy env.example to .env and create database called pinboard_app
- mysql -u root
- create database pinboard_app;


- php artisan migrate
- php artisan key:generate
- php artisan serve

To display front end navigate to http://127.0.0.1:8000/

## To run the command fetches the data from pinboard
- php artisan app:fetch-links

## Run test cases
- php artisan test