const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    ...defaultConfig,
    mode: process.env.NODE_ENV || 'development',
    devtool: process.env.NODE_ENV === 'development' ? 'source-map' : false, // 🔹 Source map hanya untuk dev
    entry: {
        admin: './src/resources/javascript/admin/index.jsx',
        frontend: './src/resources/javascript/frontend/index.jsx',
    },
    output: {
        path: path.resolve(__dirname, "assets"),
        filename: "js/[name].min.js",
    },
    optimization: {
        minimize: true,
        minimizer: [new TerserPlugin({ extractComments: false })],
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ["@babel/preset-env", "@babel/preset-react"],
                    },
                },
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    "postcss-loader"
                ]
            },
            {
                test: /\.(scss|sass)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    "sass-loader",
                    "postcss-loader"
                ]
            }
        ]
    },
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx']
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "css/[name].min.css",
        }),
    ]
};


