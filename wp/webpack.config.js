const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env, argv) => ({
    entry: path.join(__dirname, 'src/app.js'),
    output: {
        path: path.join(__dirname, 'dist/js'),
        publicPath: 'js/',
        filename: 'bundle.js'
    },
    devServer: {
        contentBase: path.join(__dirname, 'dist'),
        watchContentBase: true,
        inline: true,
        open: true
    },
    module: {
        rules: [
            //babel-loader
            {
                test: /\.js$/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-react', '@babel/preset-env']
                        }
                    }
                ],
                exclude: /node_modules/,
            },
            //css/sass-loader
            {
                test: /\.scss$/,
                use: [
                    'style-loader',
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            url: false
                        }
                    },
                    'sass-loader'
                ],
            },
        ]
    },
    plugins: [
            new MiniCssExtractPlugin({
            filename: 'style.css'
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
    ],
    resolve: {
        modules: [path.join(__dirname, 'src'), 'node_modules'],
        extensions: ['.js', '.jsx']
    },
    watch: true
});
