const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')

const ifa = {
    panelObservaciones,
    updateObservacion,
    doctosTexto,

}


function panelObservaciones(){
    $('table#main-table-ifa tbody tr').click(function(){
        let val = $(this).children().first().text()
        location.href='/SIA/juridico/observacionesIfa/'+val;
    })
}

function updateObservacion(){
    $('table#main-table-observaciones-ifa tbody tr').click(function(){
        let val = $(this).children().first().text()
        location.href='/SIA/juridico/observacionesIfa/update/'+val;
    })
}

function doctosTexto(){
    $('button#modalPromocion').click(function(){
        let promse = co(function*(){
            let datos = yield api.doctosTextos()
            let template = `<table class="table table-hover"><theader><tr>
            <th>Texto</th><th>Seleccionar</th></tr></theader>
            <tbody>`
            let td = ''
            $.each(datos,function(index,el){
                td += `<tr><td>${datos[index].texto}</td><td><input type="radio" name="texto" id="textoPromocion" value="${datos[index].idDocumentoTexto}"></td></tr>`
            })
            template = template + td + `</tbody></tables>`
            modals.promocion(template)
        })
    })
}




module.exports = ifa