const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');
module.exports = {
    pages: {
        index: {
            entry: 'src/main.js',
            template: 'public/index.html',
            filename: 'index.html',
        },
    },
    devServer: {
        clientLogLevel: 'warning',
        hot: true,
        contentBase: 'dist',
        compress: true,
        open: true,
        overlay: { warnings: false, errors: true },
        publicPath: '/',
        proxy: 'http://localhost/kwikbasket',
        quiet: true,
        watchOptions: {
            poll: true,
            ignored: /node_modules/,
        },
    },
    chainWebpack: (config) => {
        config.module
            .rule('vue')
            .use('vue-loader')
            .loader('vue-loader')
            .tap((options) => {
                options.compilerOptions.preserveWhitespace = true;
                return options;
            });
    },
    productionSourceMap: false,
   
    lintOnSave: false,
    pluginOptions: {},

    transpileDependencies: ['vue-echarts', 'resize-detector'],

    outputDir: '../public',

    indexPath: process.env.NODE_ENV === 'production' ? './template/common/index.tpl' : 'index.html',
};