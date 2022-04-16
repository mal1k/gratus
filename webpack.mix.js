const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//     .postCss('resources/css/app.css', 'public/css', [
//         //
//     ]);

mix.setPublicPath("./storage/app/public/");

// The Admin React App
mix.js("./resources/js/manager/manager.jsx", "js").react();

// The Api Docs UI
mix.js("./resources/js/api-mobile-documentor/api-mobile-documentor.jsx", "js").react();