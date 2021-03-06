/* eslint-disable no-undef */
const mix = require('laravel-mix');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

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

mix.options({
  hmrOptions: {
    host: '192.168.150.107',
    port: '8080',
  },
  postCss: [require('autoprefixer')],
});

const webpackConfigVuetify = class {
  webpackConfig(config) {
    config.plugins.push(new VuetifyLoaderPlugin());
    config.resolve.alias['@'] = path.resolve(__dirname, 'resources');
  }
};
mix.extend('vuetify', new webpackConfigVuetify());
mix.vuetify();

mix.js('resources/js/main.js', 'public/js');

if (mix.inProduction()) {
  mix.version();
}
