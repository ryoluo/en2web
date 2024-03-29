/* eslint-disable no-undef */
const path = require('path');
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
  postCss: [require('autoprefixer')],
});

// const webpackConfigVuetify = class {
//   webpackConfig(config) {
//     config.plugins.push(new VuetifyLoaderPlugin());
//     config.resolve.alias['@'] = path.resolve(__dirname, 'resources');
//   }
// };
// mix.extend('vuetify', new webpackConfigVuetify());
// mix.vuetify();
mix.webpackConfig({
  plugins: [new VuetifyLoaderPlugin()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources'),
    },
  },
  devServer: {
    host: '0.0.0.0',
    port: 8080,
  },
});

mix.js('resources/js/main.js', 'public/js').vue();

if (mix.inProduction()) {
  mix.version();
}
