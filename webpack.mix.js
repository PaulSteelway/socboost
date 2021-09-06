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

mix.js('resources/assets/js/app.js', 'public/js');
mix.js('resources/assets/js/vue.js', 'public/js');

//not free
mix.js('resources/assets/js/booster.js', 'public/js');

//free
mix.js('resources/assets/js/free.js', 'public/js');

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.sass('resources/assets/sass/admin/main_adm.scss', 'public/admin_assets/css');
mix.sass('resources/assets/sass/sprites.scss', 'public/css');

mix.options({ processCssUrls: false });

//assets
mix.copy('resources/assets/js/copy', 'public/js');
mix.copy('resources/assets/gifs', 'public/gifs');
mix.copy('resources/assets/img', 'public/img');
mix.copy('resources/assets/images', 'public/images');
mix.copy('resources/assets/svg', 'public/svg');


//admin2
mix.js('resources/assets/js/admin2.js', 'public/js/admin2.js');

mix.version().disableNotifications();
