const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const DependencyExtractionPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

// ─── Shared rules / externals ─────────────────────────────────────────────────
const babelRule = {
  test: /\.(js|jsx)$/,
  exclude: /node_modules/,
  use: {
    loader: 'babel-loader',
    options: {
      presets: [
        '@babel/preset-env',
        ['@babel/preset-react', { runtime: 'automatic' }],
      ],
    },
  },
};

const externals = {
  '@wordpress/blocks':       ['wp', 'blocks'],
  '@wordpress/block-editor': ['wp', 'blockEditor'],
  '@wordpress/components':   ['wp', 'components'],
  '@wordpress/element':      ['wp', 'element'],
  '@wordpress/i18n':         ['wp', 'i18n'],
  react:                     'React',
  'react-dom':               'ReactDOM',
};

// ─── 1. Main theme bundle (CSS + front-end JS) ────────────────────────────────
const themeConfig = {
  stats: 'errors-warnings',
  entry: {
    main:     './src/js/main.js',
    editor:   './src/js/editor.js',
    blog:     './src/js/blog/index.js',
    projects: './src/js/projects/index.js',
  },
  output: {
    path:     path.resolve(__dirname, 'dist'),
    filename: 'js/[name].js',
    clean:    false,
  },
  module: {
    rules: [
      babelRule,
      {
        test: /\.(scss|sass)$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader'],
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader'],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({ filename: 'css/[name].css' }),
  ],
  optimization: {
    minimizer: ['...', new CssMinimizerPlugin()],
  },
  resolve: {
    extensions: ['.js', '.jsx'],
    alias: { '@': path.resolve(__dirname, 'src') },
  },
  externals,
};

// ─── 2. Individual block bundles (each block → blocks/{name}/index.js) ────────
const blockConfig = {
  stats: 'errors-warnings',
  entry: {
    'hero-home':          './blocks/hero-home/src/index.js',
    'hero-interior':      './blocks/hero-interior/src/index.js',
    'section-partnership': './blocks/section-partnership/src/index.js',
  },
  output: {
    path:     path.resolve(__dirname, 'blocks'),
    filename: '[name]/index.js',
    clean:    false,
  },
  module: { rules: [babelRule] },
  plugins: [
    new DependencyExtractionPlugin({ outputFormat: 'php' }),
  ],
  resolve: {
    extensions: ['.js', '.jsx'],
    alias: { '@': path.resolve(__dirname, 'src') },
  },
  externals,
};

module.exports = [themeConfig, blockConfig];
