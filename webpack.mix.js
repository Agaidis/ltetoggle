let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
    'resources/assets/js/bootstrap.js',
    'resources/assets/js/dashboard.js',
    'resources/assets/js/permits.js',
    'resources/assets/js/mineralOwner.js',
    'resources/assets/js/datatables.min.js',


], 'public/js/app.js').version()
    .sass('resources/assets/sass/app.scss', 'public/css')
    .styles([
        'public/css/app.css',
        'resources/assets/css/dashboard.css',
        'resources/assets/css/datatables.min.css',

    ], 'public/css/app.css').version();