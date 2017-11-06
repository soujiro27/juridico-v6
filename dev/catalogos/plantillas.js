const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')


const plantilla = {
    getInsert
}

function getInsert(){
    $('table#main-table-plantillas tbody tr').click(function(){
        let val = $(this).children().first().text()
        let ruta = $(this).data('ruta');
        location.href='/SIA/juridico/'+ruta+'/add/'+val;
    })
}

module.exports = plantilla