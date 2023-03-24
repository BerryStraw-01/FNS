import MiniCssExtractPlugin from "mini-css-extract-plugin";
import CssMinimizerPlugin from "css-minimizer-webpack-plugin";
import WebpackRemoveEmptyScriptsPlugin from "webpack-remove-empty-scripts";

module.exports = {
    // 入力ファイル設定
    entry: {
        bundle: './src-ts/index.ts',
        style: './src-css/style.scss',
    },
    devtool: 'source-map',
    mode: 'development',
    cache: true,
    module: {
        rules: [
            {
                // ts-loaderの設定
                test: /\.(ts|tsx)?$/,
                use: "ts-loader",
                exclude: /node_modules/
            },
            {
                test: /\.(scss|css)?$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader",
                    "postcss-loader",
                    "sass-loader",
                ]
            },
        ]
    },

    optimization: {
        minimizer: [
            new CssMinimizerPlugin(),
        ],
    },

    // モジュール設定
    output: {
        path: __dirname,
        filename: './public/build/[name].js'
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: './public/build/[name].css'
        }),
        new WebpackRemoveEmptyScriptsPlugin({}),
    ],

    // モジュール解決
    resolve: {
        extensions: [".ts", ".tsx", ".js", ".json"]
    },
};
