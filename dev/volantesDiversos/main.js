require('babelify-es6-polyfill')
const $ = require('jquery')
const utils = require('./utils')

utils.update()
utils.cancel()
utils.getSub()
utils.nota()
$('input.fechaInput').datepicker({ dateFormat: "yy-mm-dd" });