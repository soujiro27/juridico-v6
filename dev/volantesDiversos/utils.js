require("jquery-ui-browserify");
const $ = require('jquery')
const confirm = require('jquery-confirm')
const co = require('co')
const Promise = require('bluebird')

const api = require('./api')
const utils = {
    update,
    cancel,
    getSub,
    nota
}

function update(){
    $('table#main-table tbody tr').click(function(){
        let val = $(this).children().first().text()
        let ruta = $(this).data('ruta');
        location.href=`./${ruta}/update/${val}`;
    })
}

function cancel(){
    $('button#cancelar').click(function(e){
        e.preventDefault()
        let ruta = $('form').data('ruta')
        location.href='../'+ruta;
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

function nota(){
    $('select#subDocumento').change(function(){
        let texto = $('select#subDocumento option:selected').text()
        if(texto == 'CONFRONTA')
        {
            $.alert({
                title: '¿Contiene NOTA INFORMATIVA?',
                theme:'modern',
                content:'',
                buttons:{
                    confirm:{
                        btnClass:'btn-primary',
                        text: 'SI',
                        action:function(){
                            $('input#notaConfronta').val('SI')
                        }},
                    cancel:{
                        btnClass:'btn-danger',
                        text:'NO',
                        action:function(){
                            $('input#notaConfronta').val('NO')
                        }}}})
        }
    })
}

module.exports = utils