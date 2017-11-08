const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')


const irac = {
    panelObservaciones,
    updateObservacion,
    firmas
}


function panelObservaciones(){
    $('table#main-table-irac tbody tr').click(function(){
        let val = $(this).children().first().text()
        location.href='/SIA/juridico/observacionesIrac/'+val;
    })
}

function updateObservacion(){
    $('table#main-table-observaciones-irac tbody tr').click(function(){
        let val = $(this).children().first().text()
        location.href='/SIA/juridico/observacionesIrac/update/'+val;
    })
}

function firmas(){

    $('button#modalFirmas').click(function(){
        let promesa = co(function *(){
            let firmas  = yield api.firmas()
            let template = `<table class="table table-hover"><theader><tr>
            <th>Nombre</th><th>Puesto</th><th>Seleccionar</th></tr></theader>
            <tbody>`
            let td = ''
            $.each(firmas,function(index,el){
                td += `<tr><td>${firmas[index].saludo} ${firmas[index].nombre} ${firmas[index].paterno} ${firmas[index].materno}</td>
                <td>${firmas[index].puesto}</td><td><input type="checkbox" name="firmas" id="firmas" value="${firmas[index].idPuestoJuridico}"></td></tr>`
            })
            template = template + td + `</tbody></tables>`
            modals.firmas(template)
        })
    })

    
}


module.exports =irac