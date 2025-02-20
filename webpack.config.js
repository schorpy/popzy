const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    ...defaultConfig,
    mode: process.env.NODE_ENV || 'development',  // 🔹 Sesuaikan mode berdasarkan environment
    devtool: process.env.NODE_ENV === 'development' ? 'source-map' : false, // 🔹 Source map hanya untuk dev
    entry: {
        admin: './src/resources/javascript/admin/index.jsx',
        frontend: './src/resources/javascript/frontend/index.jsx',
    },
    output: {
        path: path.resolve(__dirname, "assets"), // Semua file ke dalam folder "assets"
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
                test: /\.css$/,  // 🔹 Tangani file .css
                use: [
                    MiniCssExtractPlugin.loader, // Ekstrak CSS ke file
                    "css-loader", // Menafsirkan @import & url()
                    "postcss-loader" // Menjalankan PostCSS (untuk Tailwind)
                ]
            },
            {
                test: /\.(scss|sass)$/,  // 🔹 Tangani file .scss dan .sass
                use: [
                    MiniCssExtractPlugin.loader, // Ekstrak CSS ke file
                    "css-loader", // Menafsirkan @import & url()
                    "sass-loader",  // Mengonversi SCSS ke CSS
                    "postcss-loader" // Menjalankan PostCSS (untuk Tailwind)
                ]
            }
        ]
    },
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx']
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "css/[name].min.css", // Semua CSS ke "assets/css/"
        }),
    ]
};

// plugins: [
//     new MiniCssExtractPlugin(),
//     // {

//     //     apply: (compiler) => {
//     //         compiler.hooks.done.tap('ZipPlugin', (stats) => {
//     //             if (stats.compilation.options.mode === 'production') { // ✅ Only in build mode
//     //                 const zipPath = path.join(__dirname, 'artistudio-popup.zip');
//     //                 const output = fs.createWriteStream(zipPath);
//     //                 const archive = archiver('zip', { zlib: { level: 9 } });

//     //                 output.on('close', () => console.log(`✅ ZIP created: ${zipPath} (${archive.pointer()} bytes)`));
//     //                 archive.on('error', (err) => console.error('❌ ZIP error:', err));

//     //                 archive.pipe(output);
//     //                 archive.directory('build', 'artistudio-popup');
//     //                 archive.finalize();
//     //             }
//     //         });
//     //     }
//     // }
// ]

