const path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
	entry: [
		'babel-polyfill',
		'./src/js/main.js'
	],
	output: {
		filename: 'app.js',
		path: path.resolve(__dirname, 'web')
	},
	plugins: [
		new ExtractTextPlugin('app.css'),
		new webpack.optimize.UglifyJsPlugin({
			compress: {
				warnings: false
			}
		}),
		new webpack.DefinePlugin({
			'process.env':{
				'NODE_ENV': JSON.stringify('production')
			}
		}),
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jquery',
			Popper: ['popper.js', 'default']
		})
	],
	module: {
		rules: [
			{ 
				test: /\.js?$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				options: {
					presets: ['env'],
					plugins: ['transform-runtime']
				}
			},
			{
				test: /\.css$/,
				use: ExtractTextPlugin.extract({
					fallback: 'style-loader',
					use: 'css-loader'
				})
			},
			{
				test: /\.(png|svg|jpg|gif)$/,
				use: [
					'file-loader'
				]
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				use: [
					'file-loader'
				]
			}
		]
	}
};