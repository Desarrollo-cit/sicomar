const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
  mode: 'development',
  watch: true,
  entry: {
    'js/app' : './src/js/app.js',
    'js/inicio' : './src/js/inicio.js',
    'js/Reporte/index' : './src/js/Reporte/index.js',
    'js/Reporte/derrota' : './src/js/Reporte/derrota.js',
    'js/Reporte/motores' : './src/js/Reporte/motores.js',
    'js/Reporte/consumos' : './src/js/Reporte/consumos.js',
    'js/Reporte/comunicaciones' : './src/js/Reporte/comunicaciones.js',
    'js/Reporte/novedades' : './src/js/Reporte/novedades.js',
    'js/Reporte/lecciones' : './src/js/Reporte/lecciones.js',
    'js/Reporte/inteligencia' : './src/js/Reporte/inteligencia.js',
    'js/internacionales/index' : './src/js/internacionales/index.js',
    'js/validacionR/index' : './src/js/ValidacionR/index.js',
    'js/validacionO/index' : './src/js/ValidacionO/index.js',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'public/build')
  },
  plugins: [
    new MiniCssExtractPlugin({
        filename: 'styles.css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(c|sc|sa)ss$/,
        use: [
            {
                loader: MiniCssExtractPlugin.loader
            },
            'css-loader',
            'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        loader: 'file-loader',
        options: {
           name: 'img/[name].[hash:7].[ext]'
        }
      },
    ]
  }
};