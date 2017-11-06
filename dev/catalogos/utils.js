
require('ckeditor')
const $ = require('jquery')
const api = require('./api')
const co = require('co')
const Promise = require('bluebird')
const utils = {
    update,
    cancel,
    getSub,
    ckeditorLoad
}

function update(){
    $('table#main-table tbody tr').click(function(){
        let val = $(this).children().first().text()
        let ruta = $(this).data('ruta');
        location.href='/SIA/juridico/'+ruta+'/update/'+val;
    })
}

function cancel(){
    $('button#cancelar').click(function(e){
        e.preventDefault()
        let ruta = $('form').data('ruta')
        location.href=`/SIA/juridico/${ruta}`
    })
}

function getSub(){
    $('select#idDocumento').change(function(){
        let val = $(this).val()
        let promesa = co (function*(){
            let json = yield api.subDocumentos(val)
            let opt = '<option value=""> Seleccione una Opcion </option>'
            $.each(json,function(index,el){
                opt += `<option value=${json[index].idSubTipoDocumento}>${json[index].nombre}</option>`
            })
              $('select#subDocumento').html(opt)  
        })     
        })
}

function ckeditorLoad(){
    try{
        CKEDITOR.disableAutoInline=true
        CKEDITOR.replace('ckeditor')
    }
    catch(err){
        console.log(err)
    }
}

module.exports = utils