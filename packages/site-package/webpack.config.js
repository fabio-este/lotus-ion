var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('../../public/build')
    // public path used by the web server to access the output path
    .setPublicPath('/build/')

    // only needed for CDN's or sub-directory deploy
    .setManifestKeyPrefix('')

    .addEntry('App', './Resources/Public/JavaScript/app.js')
    .addEntry('FontAwesome', './Resources/Public/JavaScript/FontAwesome.js')
    .addStyleEntry('Theme', './Resources/Public/Scss/theme.scss')
    .autoProvidejQuery()


    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    .splitEntryChunks()

    .cleanupOutputBeforeBuild()
    //.enableSourceMaps(!Encore.isProduction())
    .enableSourceMaps(true)
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .enableSassLoader()

    .copyFiles([
        {from: 'Resources/Public/Fonts', to: 'Fonts/[path][name].[ext]'},
        {from: 'Resources/Public/Icons', to: 'Icons/[path][name].[ext]'},
        {from: 'Resources/Public/Images', to: 'Images/[path][name].[ext]'},
        {from: 'Resources/Public/Css', to: 'Css/[path][name].[ext]'},
    ])
;

module.exports = Encore.getWebpackConfig();
