
## Pinboard App Test Project

<img width="1013" alt="image" src="https://github.com/mrmacsi/pinboard-app/assets/17278863/46313ecf-8c35-4f88-8c0a-dac585bc3ec6">
<img width="1013" alt="image" src="https://github.com/mrmacsi/pinboard-app/assets/17278863/c5514688-ab0a-40ed-811b-ff5fd72b91c5">

## Commands to run for serving the application

- composer install
- npm install && npm run dev

## Migrate the database

- Copy env.example to .env and create a database called pinboard_app
- mysql -u root
- create database pinboard_app;


- php artisan migrate
- php artisan key:generate
- php artisan serve

To display the front end navigate to http://127.0.0.1:8000/

## To run the command fetches the data from pinboard
- php artisan app:fetch-links

<img width="619" alt="image" src="https://github.com/mrmacsi/pinboard-app/assets/17278863/952182fc-c7ed-48fc-93e0-fafd5f6a56ac">

## Run test cases
- php artisan test

<img width="524" alt="image" src="https://github.com/mrmacsi/pinboard-app/assets/17278863/29d9d2a9-1bc5-4b4a-8ecb-b67fdc722f3a">

