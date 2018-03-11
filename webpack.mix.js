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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .copyDirectory('resources/assets/css/themes', 'public/css/themes')
   .styles([
      'resources/assets/css/semantic.css',
      'resources/assets/css/components/calendar.css'
   ], 'public/css/semantic.css')
   .styles([
   		'resources/assets/css/app/header.css',
   		'resources/assets/css/app/common.css',
   		'resources/assets/css/app/footer.css',
   	], 'public/css/app/all.css')
   .styles(['resources/assets/css/app/event.css'], 'public/css/app/event.css')
   .styles(['resources/assets/css/app/event_page.css'], 'public/css/app/event_page.css')
   .styles(['resources/assets/css/app/event_creation.css'], 'public/css/app/event_creation.css')
   .scripts([
      'resources/assets/js/semantic.js',
      'resources/assets/js/components/calendar.js'
   ], 'public/js/semantic.js')
   .scripts(['resources/assets/js/app/event.js'], 'public/js/app/event.js')
   .scripts(['resources/assets/js/app/event_creation.js'], 'public/js/app/event_creation.js');
