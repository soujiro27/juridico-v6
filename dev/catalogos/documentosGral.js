const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')

const documentosGral = {
    volantesByFolio,
    download
}

function volantesByFolio(){
    $('input#folio-documentos').keyup(function(){
        let val = $(this).val()
        let promesa = co(function*(){
            let volantes = yield api.volanteDocumentos({folio:val})
            $('input#idVolante').val(volantes[0].idVolante)
        })
    })
}
function download(){
    $('table#main-table-files tbody tr').click(function(){
        let val = $(this).children().first().next().text()
        let promesa = co(function*(){
            let volantes = yield api.volanteDocumentos({folio:val})
            $('input#idVolante').val(volantes[0].idVolante)
            window.open('/SIA/juridico/public/files/'+volantes[0].anexoDoc)
           
        })
    })
}


module.exports = documentosGral