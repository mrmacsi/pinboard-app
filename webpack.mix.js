const mix = require('laravel-mix');
mix.js('resources/js/app.js', 'public/js').vue();
mix.js('resources/css/app.css', 'public/css').vue();