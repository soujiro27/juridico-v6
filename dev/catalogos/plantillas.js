const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')


const plantilla = {
    getInsert,
    internos,
    externos
}

function getInsert(){
    $('table#main-table-plantillas tbody tr').click(function(){
        let val = $(this).children().first().text()
        let ruta = $(this).data('ruta');
        location.href='/SIA/juridico/'+ruta+'/add/'+val;
    })
}

function internos(){
    $('button#modalInternos').click(function(e){
        e.preventDefault()
        let promesa = co(function*(){
            let datos = yield api.remitentesPlantillas({tipo:'I'})
            let template = `<table class="table table-hover"><theader><tr>
            <th>Nombre</th><th>Puesto</th><th>Seleccionar</th></tr></theader>
            <tbody>`
            let td = ''
            $.each(datos,function(index,el){
                td += `<tr><td>${datos[index].saludo} ${datos[index].nombre}</td><td>${datos[index].puesto}</td>
                <td><input type="checkbox" name="ccpInternos" id="ccpInternos" value="${datos[index].idRemitenteJuridico}"></td></tr>`
            })
            template = template + td + `</tbody></tables>`
            modals.internos(template)
        })
    })
}

function externos(){
    $('button#modalExternos').click(function(e){
        e.preventDefault()
        let promesa = co(function*(){
            let datos = yield api.remitentesPlantillas({tipo:'E'})
            let template = `<table class="table table-hover"><theader><tr>
            <th>Nombre</th><th>Puesto</th><th>Seleccionar</th></tr></theader>
            <tbody>`
            let td = ''
            $.each(datos,function(index,el){
                td += `<tr><td>${datos[index].saludo} ${datos[index].nombre}</td><td>${datos[index].puesto}</td>
                <td><input type="checkbox" name="ccpExternos" id="ccpExternos" value="${datos[index].idRemitenteJuridico}"></td></tr>`
            })
            template = template + td + `</tbody></tables>`
            modals.externos(template)
        })
    })
}

module.exports = plantilla