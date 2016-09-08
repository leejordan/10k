var compressor = require('./node_modules/node-minify/lib/node-minify');

new compressor.minify({
    type: 'yui-js',
    fileIn: 'js/10ktube.js',
    fileOut: 'js/10ktube.min.js',
    callback: function(err, min){
        console.log(err);
    }
});

new compressor.minify({
    type: 'yui-css',
    fileIn: 'css/10ktube.css',
    fileOut: 'css/10ktube.min.css',
    callback: function(err, min){
        console.log(err);
    }
});
