const mix = require('laravel-mix');

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

mix.styles([
    'resources/assets/plantilla/css/simple-line-icons.min.css',
    'resources/assets/plantilla/css/style.css',
 ], 'public/css/plantilla.css')
 .scripts([
    
    'resources/assets/plantilla/js/jquery.min.js',
    'resources/assets/plantilla/js/jquery.easing.min.js',
    'resources/assets/plantilla/js/Chart.min.js',
    'resources/assets/plantilla/js/sb-admin-2.min.js',
     'resources/assets/plantilla/js/bootstrap.bundle.min.js'
], 'public/js/plantilla.js')
.js(['resources/js/app.js'], 'public/js/app.js');
