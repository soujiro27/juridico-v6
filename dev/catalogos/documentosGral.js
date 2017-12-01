const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')

const documentosGral = {
    volantesByFolio,
    download,
    download_sub
}

function volantesByFolio(){
    $('input#subFolio-documentos').keyup(function(){
        let val = $('input#folio-documentos').val()
        let sub = $(this).val()
        let promesa = co(function*(){
            let volantes = yield api.volanteDocumentos({folio:val,subFolio:sub})
            $('input#idVolante').val(volantes[0].idVolante)
            let file = volantes[0].anexoDoc
           if(file != null){
                $('input#archivo').val(file)
           }else{
            $('input#archivo').val('Sin Asignar')
           }
        })
    })
}
/*
function download(){
    $('table#main-table-files tbody tr').click(function(){
        let val = $(this).children().first().next().text()
        let sub = $(this).children().first().next().next().text()
        let promesa = co(function*(){
            let volantes = yield api.volanteDocumentos({folio:val,subFolio:sub})
            $('input#idVolante').val(volantes[0].idVolante)
            if(volantes[0].anexoDoc != null){
                window.open('/SIA/juridico/public/files/'+volantes[0].anexoDoc)

            }
           
        })
    })
}*/

function download(){
    $('table#main-table-files tbody tr').click(function(){
        let val = $(this).children().first().text()
        location.href = `/SIA/juridico/DocumentosGral/update/${val}`
    })
}


function download_sub(){
    $('table#main-table-files-sub tbody tr').click(function(){
        let val = $(this).children().first().text()
        location.href = `/SIA/juridico/Documentos/update/${val}`
    })
}

module.exports = documentosGral