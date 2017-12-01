const $ = require('jquery')
const co = require('co')
const Promise = require('bluebird')
const api = require('./api')
const modals = require('./../modals/modals')


const plantilla = {
    getInsert,
    internos,
    externos,
    puestos
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

function puestos(){
    $('button#modalPuestoJuridico').click(function(e){
        e.preventDefault()
        let promesa = co(function *(){
            let datos = yield api.puestos()
            let template = `<table class="table table-hover puestos">
            <thead><tr><th>Escoger</th><th>Nombre</th><th>Puesto</th></thead>
            <tbody>`
            let td = ''
            $.each(datos,function(index,el){
                td += `<tr> <td><input type="radio" name="puesto"
                value="${datos[index].idPuestoJuridico}" data-id="${datos[index].idPuestoJuridico}"></td>
                <td>${datos[index].nombre} ${datos[index].paterno} ${datos[index].materno}</td>
                <td>${datos[index].puesto}</td>
                </tr>`
            })
            template = template + td + '</tbody></table>'
            modals.puestos(template)
        })
    })
}

module.exports = plantilla