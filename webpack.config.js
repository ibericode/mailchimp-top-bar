const path = require('path')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const cssnano = require('cssnano')
const postcss = require('postcss')

module.exports = {
  entry: {
    script: './assets/src/js/script.js',
    admin: './assets/src/js/admin.js'
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'assets')
  },
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader']
      }
    ]
  },
  plugins: [
    new CopyWebpackPlugin({
      patterns: [
        {
          from: 'assets/src/css/*.css',
          to: path.resolve(__dirname, 'assets') + '/[name].css',
          transform: (content, path) => {
            return postcss([cssnano])
              .process(content, {
                from: path
              })
              .then((result) => {
                return result.css
              })
          }
        }
      ]
    })
  ]
}
