
const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')

const utils = {
    getSubDocumentos,
    nota,
    auditoria,
    remitentes,
    getSubDocumentosSinAuditoria,
    order,
    cerrarVolante
}

function getSubDocumentos(){
    $('select#idDocumentoAuditoria').change(function(){
        let val = $(this).val()
        let promesa = co (function*(){
            let json = yield api.subDocumentosAuditoria(val)
            let opt = '<option value=""> Seleccione una Opcion </option>'
            $.each(json,function(index,el){
                opt += `<option value=${json[index].idSubTipoDocumento}>${json[index].nombre}</option>`
            })
              $('select#subDocumento').html(opt)  
        })     
        })
}

function nota(){
    $('select#subDocumento').change(function(){
        let texto = $('select#subDocumento option:selected').text()
        if(texto == 'CONFRONTA')
        {
           modals.nota()
        }
        else if(texto == 'DICTAMEN'){
            modals.dictamen()
        }
        else{
            $('input#notaConfronta').val('NO')
        }
    })
}


function auditoria(){
    $('button#modalAuditoria').click(function(){
        modals.auditoria()
    })
}

function remitentes() {
    $('button#remitente').click(function(e){
        e.preventDefault();
        let promesa = co (function *(){
            let remitentes =  yield api.remitentes()
            let template = `<table class="table table-hover remitentes">
            <thead><tr><th>Escoger</th><th>Tipo</th><th>Nombre</th><th>Puesto</th></thead>
            <tbody>`
            let td = ''
            $.each(remitentes,function(index,el){
                td += `<tr> <td><input type="radio" name="remitente" data-nombre="${remitentes[index].nombre}"
                data-puesto="${remitentes[index].puesto}" 
                value="${remitentes[index].siglasArea}" data-id="${remitentes[index].idRemitenteJuridico}"></td>
                <td>${remitentes[index].tipoRemitente}</td>
                <td>${remitentes[index].nombre}</td>
                <td>${remitentes[index].puesto}</td>
                </tr>`
            })
            template = template + td + '</tbody></table>'
            modals.remitentes(template)  
        })
    })
}

function getSubDocumentosSinAuditoria(){
    $('select#volantesDiversos').change(function(){
        let val = $(this).val()
        let promesa = co (function*(){
            let json = yield api.subDocumentosNoAuditoria(val)
            let opt = '<option value=""> Seleccione una Opcion </option>'
            $.each(json,function(index,el){
                opt += `<option value=${json[index].idSubTipoDocumento}>${json[index].nombre}</option>`
            })
              $('select#subDocumento').html(opt)  
        })     
        })
}


function order(){
    $('button#btn-order').click(function(){
        $('form#form-order').toggle();
    })
}



function cerrarVolante(){
    $('a#btn-close-volante').click(function(e){
        e.preventDefault()
        let val = $(this).data('id')
        let ruta = $(this).data('ruta')
        modals.closeVolante(val,ruta)
    })
}

module.exports = utils