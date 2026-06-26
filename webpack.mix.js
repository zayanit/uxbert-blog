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
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

// elliptic (<=6.6.1) has no patched version on npm. The vulnerable chain is:
// elliptic → browserify-sign, create-ecdh → crypto-browserify → node-libs-browser
// node-libs-browser is a webpack 4 era package; webpack 5 (used by laravel-mix 6)
// no longer requires these Node.js polyfills by default. Since this project has no
// browser-side Node.js crypto usage, explicitly disabling these fallbacks removes
// the entire vulnerable dependency chain.
mix.webpackConfig({
    resolve: {
        fallback: {
            crypto: false,
            stream: false,
            assert: false,
            http: false,
            https: false,
            os: false,
            url: false,
            zlib: false,
        }
    }
});
