
const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')

const confronta = {
    cedula
}

function cedula(){
    $('table#main-table-confronta tbody tr').click(function(){
        let val = $(this).children().first().text()
        let ruta = $(this).data('ruta');
        location.href='/SIA/juridico/'+ruta+'/add/'+val;
    })
}

module.exports = confronta