const path = require('path');
const webpack = require('webpack');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

let plugins = [];

plugins.push(new webpack.ContextReplacementPlugin(/moment[/\\]locale$/, /en-gb/));
if (process.env.NODE_ENV === 'production') {
    // plugins.push(new BundleAnalyzerPlugin());
}

module.exports = {
    resolve: {
        alias: {
            '@scripts': path.resolve(__dirname, 'resources/scripts'),
            '@images': path.resolve(__dirname, 'resources/assets/images'),
        },
    },
    plugins,
};
