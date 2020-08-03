const mix = require('laravel-mix');

//Minifica bootstrap que usa el login
mix.styles('resources/css/bootstrap.css', 'public/css/bootstrap.min.css');

//Minifica el css y js de la interfaz del sistema
mix.styles([
    'resources/assets/plantilla/css/sb-admin-2.min.css',
    'resources/css/animate.min.css',
    'resources/css/styles.css',
], 'public/css/plantilla.css')
.scripts([
   'resources/assets/plantilla/js/jquery.easing.min.js',
   'resources/assets/plantilla/js/Chart.min.js',
   'resources/assets/plantilla/js/sb-admin-2.min.js',
   'resources/js/library.js',
], 'public/js/plantilla.js')
.js(['resources/js/app.js'], 'public/js/app.js');
