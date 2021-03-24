const mix = require('laravel-mix');
const webpack = require('./webpack.config.js');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

require('vuetifyjs-mix-extension');
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
mix
    .extend(
        'vuetify',
        new (class {webpackConfig(config) {config.plugins.push(new VuetifyLoaderPlugin());}})()
    )
    .js('./resources/scripts/app.js', './public/js')
    .vuetify()
    .sass('./resources/styles/app.scss', './public/css')
    .options({
        processCssUrls: false,
    })
    .copyDirectory('./resources/assets', './public/assets')
    // .copyDirectory('./node_modules/@mdi/font/fonts', './public/assets/fonts')
    // .copyDirectory('./node_modules/font-awesome/fonts', './public/assets/fonts/')
    .sourceMaps()
    .webpackConfig(Object.assign(webpack))
    .options({
        extractVueStyles: true,
    })
    .version();
