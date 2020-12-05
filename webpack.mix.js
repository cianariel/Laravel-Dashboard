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

mix.sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/framework.js', 'public/js')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .copy('resources/fonts', 'public/webfonts')
    .copy('node_modules/summernote/dist/font', 'public/css/font')
    .copy('node_modules/summernote/dist/lang', 'public/js/lang')
    .copy('resources/images', 'public/images')
    .options({
      processCssUrls: false
    })
    .extract([
        'lodash',
        'axios',
        'popper.js',
        'jquery',
        'bootstrap',
        'js-cookie',
        'autosize',
        'timepicker',
        'daterangepicker',
        'owl.carousel',
        'parsleyjs',
        'summernote',
        'dragula',
        'inputmask',
        'chart.js',
        'jquery-match-height',
        'jquery-outside-events',
    ])
    .version();
