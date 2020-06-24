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

mix.js('resources/js/app.js', 'public/js')
<<<<<<< HEAD
   .sass('resources/sass/app.scss', 'public/css')
   .version();
   
=======
   .sass('resources/sass/app.scss', 'public/css');
>>>>>>> b11d4baebf0e8748441f1508f794d5e39d48eea0
