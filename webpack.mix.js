let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('dotenv').config();

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

mix.js('resources/assets/js/app.js', 'public/js');

mix.sass('resources/assets/sass/app.scss', 'public/css')
    .combine(jslibs, './public/js/libs.js')
    .combine(csslibs, './public/css/libs.css')
    .options({
            processCssUrls: false,
            postCss: [tailwindcss('./tailwind.js')],
        })
        .browserSync(process.env.DEV_URL)
//    .sourceMaps();
