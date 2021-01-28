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
        proxy: 'http://www.kwikbasket.local',
        quiet: true,
        watchOptions: {
            poll: true,
            ignored: /node_modules/,
        },
    },

    publicPath: process.env.NODE_ENV === 'production' ? '/public/' : '/',

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

    outputDir: '../../../../public',

    indexPath: process.env.NODE_ENV === 'production' ? 'index.tpl' : 'index.html',
};