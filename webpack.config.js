var webpack = require('webpack');
var CommonsChunkPlugin = webpack.optimize.CommonsChunkPlugin;
var UglifyJsPlugin = webpack.optimize.UglifyJsPlugin;
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var path = require('path');
 
var basedir = __dirname + '/src/JA/AppBundle/Resources/public/assets/';

var entry = {
  home: 'home',
  user: 'User/user',
  game: 'Game/game',
};
 
for(i in entry) {
  entry[i] = basedir + entry[i];
}
 
module.exports = {
    entry: entry,
    output: {
        path: "./web/assets/",
		publicPath: "/assets",
        filename: "[name].js",
        chunkFilename: "[id].js",
    },
    devtool: "source-map",
    module: {
        loaders: [
            { test: /\.scss/, loader: ExtractTextPlugin.extract("style", "css?sourceMap!sass?sourceMap") },
            { test: /\.less/, loader: ExtractTextPlugin.extract("style", "css?sourceMap!less?sourceMap") },

            { test: /\.woff(2)?(\?v=\d+\.\d+\.\d+)?$/,   loader: "url?limit=10000&mimetype=application/font-woff&name=fonts/[name].[ext]" },
            { test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,    loader: "url?limit=10000&mimetype=application/octet-stream&name=fonts/[name].[ext]" },
            { test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,    loader: "file?name=fonts/[name].[ext]" },
            { test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,    loader: "url?limit=10000&mimetype=image/svg+xml&name=fonts/[name].[ext]" }
        ]
    },
    plugins: [
        new CommonsChunkPlugin("commons", "commons.js", ["home", "user", "game"]),
        new ExtractTextPlugin("[name].css"),
//        new UglifyJsPlugin(),// sass problem
    ],
    resolve: {
        //alias: { bootstrap: "bootstrap-sass/assets/stylesheets/bootstrap" }, // when sass problems resolved
    },
    devServer: {
        contentBase: "./web",
        port: 8001,
    },
};
