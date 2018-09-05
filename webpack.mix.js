let mix = require('laravel-mix')
const path = require('path')
const UglifyJSPlugin = require('uglifyjs-webpack-plugin')
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const imageminMozjpeg = require('imagemin-mozjpeg');

module.exports = {
    module:
    {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        js: 'babel-loader'
                    }
                }
            },
            {
                test: /\.css$/,
                loaders: [
                    'style-loader',
                    'css-loader'
                ]
            },
            {
                test: /\.less$/,
                use: [{
                    loader: 'style-loader' // creates style nodes from JS strings
                }, {
                    loader: 'css-loader' // translates CSS into CommonJS
                }, {
                    loader: 'less-loader' // compiles Less to CSS
                }]
            },
            {
                test: /\.(eot|woff|woff2|ttf|otf)$/,
                loaders: [
                    'url-loader'
                ]
            },
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: [{
                    loader: "babel-loader",
                    options: { presets: ['es2015'] }
                }]
            },
            {
                test: /\.(gif|png|jpe?g|svg)$/i,
                use: [
                    'file-loader',
                    {
                        loader: 'image-webpack-loader',
                    },
                ],
            },
            {
                test: /\.(mov|mp4)$/,
                use: [
                    {
                        loader: 'url-loader',
                    }
                ]
            }
        ]
    },
    resolve: {
        alias: {
            // Ensure the right Vue build is used
            'vue$': 'vue/dist/vue.esm.js'
        },
        extensions: ['*', '.js', '.vue', '.json']
    },
    entry: './resources/assets/js/app.js',
    output: {
        path: path.resolve(__dirname) + '/public/js/',
        publicPath: 'public/js/dist/',
        filename: 'bundle.js'
    },
    plugins: [
        //new VueLoaderPlugin()
        new UglifyJSPlugin()
    ],
}

mix.webpackConfig({
    plugins: [
        new CopyWebpackPlugin([{
            from: 'resources/assets/images',
            to: 'img', // Laravel mix will place this in 'public/img'
        }]),
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i,
            plugins: [
                imageminMozjpeg({
                    quality: 80,
                })
            ]
        })
    ]
});

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
   .less('resources/assets/less/styles.less', 'public/css');
