let mix = require('laravel-mix');

// Add the JS part of your libs
var jslibs = [
];

// Add the CSS part of your libs
var csslibs = [
];

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
   .combine(jslibs, './public/js/libs.js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .combine(csslibs, './public/css/libs.css')
   .sourceMaps();
