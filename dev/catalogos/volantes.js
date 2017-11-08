
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
    getSubDocumentosSinAuditoria
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
            <thead><tr><th>Tipo</th><th>Nombre</th><th>Puesto</th><th>Escoger</th></thead>
            <tbody>`
            let td = ''
            $.each(remitentes,function(index,el){
                td += `<tr><td>${remitentes[index].tipoRemitente}</td>
                <td>${remitentes[index].nombre}</td>
                <td>${remitentes[index].puesto}</td>
                <td><input type="radio" name="remitente" value="${remitentes[index].idRemitenteJuridico}"></td>
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

module.exports = utils