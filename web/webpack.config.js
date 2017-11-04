const path = require('path');

module.exports = {
    entry: path.join(__dirname, 'test', 'index.js'),
    output: {
        path: 'public',
        filename: 'bundle.js',
    },
};