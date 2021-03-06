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

mix.disableSuccessNotifications();

mix.js('resources/assets/js/app.js', 'public/js')
    .copy('public/img/default_cover_photo.png', 'public/storage/users/cover_photos')
    .copy('public/img/mysteryman.png', 'public/storage/users/photos')
    .copy('node_modules/autosize/dist/autosize.min.js', 'public/js')
    .copy('node_modules/clipboard/dist/clipboard.min.js', 'public/js')
    .copy('node_modules/bootstrap-maxlength/bootstrap-maxlength.min.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .version()
    .browserSync({
        proxy: 'http://twitterclone.test',
        notify: false,
    });

if (process.env.NODE_ENV === 'development') {
    mix.webpackConfig({
        module: {
            rules: [
                {
                    test: /\\.(js)$/,
                    exclude: /node_modules/,
                    loader: 'eslint-loader',
                },
            ],
        },
    });
}