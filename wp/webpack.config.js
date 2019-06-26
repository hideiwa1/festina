const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env, argv) => ({
    entry: path.join(__dirname, 'src/app.js'),
    output: {
        path: path.join(__dirname, 'dist/js'),
        filename: 'bundle.js'
    },
    devServer: {
        contentBase: path.join(__dirname, 'dist'),
        watchContentBase: true,
        inline: true,
        publicPath: '/js/',
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
                    /*
                    MiniCssExtractPlugin.loader,
                    */
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
    /*
    plugins: [
            new MiniCssExtractPlugin({
            filename: 'style.css'
        }),
    ],
    */
    resolve: {
        modules: [path.join(__dirname, 'src'), 'node_modules'],
        extensions: ['.js', '.jsx']
    },
    watch: true
});
