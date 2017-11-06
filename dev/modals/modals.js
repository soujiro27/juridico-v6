require("jquery-ui-browserify");
const $ = require('jquery')
const confirm = require('jquery-confirm')
const co = require('co')
const Promise = require('bluebird')
const api = require('./../catalogos/api')
const volantes = require('./../catalogos/volantes')
const modals = {
    nota,
    auditoria,
    TableDatosAuditoria,
    tableTurnados,
    remitentes,
    firmas
}

function nota(){
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

function auditoria(){
    let self = this
        $.confirm({
            title: 'Seleccione el Numero de Auditoria',
            theme:'modern',
            content: `<div class="auditoria-container">
            <div class="auditoria">
              <div class="cuenta">
                <p class="cuenta">CUENTA PUBLICA 2016</p>
              </div>
              <div class="search"><span>ASCM/</span>
                <input id="auditoria" type="text" name="auditoria"/><span>16</span>
              </div>
            </div>
            <div class="datosAuditoria"></div>
            <div class="asignacion"></div>
          </div>`,
            buttons:{
                confirm:{
                    text: 'Aceptar',
                    btnClass:'btn-success'
                    
                },
                cancel:{
                    text:'Cancelar',
                    btnClass: 'btn-danger'
                }
            },
            onOpenBefore:function(){
                    $('input#auditoria').keyup(function(){
                        let cve = `ASCM/${$(this).val()}/16`
                        if(cve != ''){

                            let datosAuditoria = co(function *(){
                                let datos = yield api.getAuditoria({clave:cve})
                                let turnado = yield api.getTurnadoAuditoria({cveAuditoria:datos[0].idAuditoria})
                                
                                let table = self.TableDatosAuditoria(datos)
                                $('div.datosAuditoria').html(table)
                                
                                let tableTurnado = self.tableTurnados(datos)
                                $('div.asignacion').html(tableTurnado)
                                
                                $('p#textoCveAuditoria').text(cve)
                                $('input#cveAuditoria').val(datos[0].idAuditoria)
                                $('input#idRemitente').val(datos[0].idArea)
                                
                            })
                        }
                    })
              
            }
        })
  
}



function TableDatosAuditoria(datos){
    let template =`
    <table class="datosAuditoria">
      <thead>
        <tr>
          <th>Sujeto</th>
          <th>Rubros</th>
          <th>Tipo</th>
        </tr>
      </thead>
      <tbody>:datos</tbody>
    </table>`
    let campos = `<tr><td>${datos[0].sujeto}</td><td>${datos[0].rubros}</td><td>${datos[0].tipo}</td></tr>`
    let res = template.replace(':datos',campos)
    return res
    
}


function tableTurnados(datos){
    let body
     if (datos.length>0){
        body = `<tr>`
        for(let x in datos){
            if(datos[x].nombre=='IRAC'){
                body += `<td>${datos[x].turnado}</td>`
            }
            else if(datos[x].nombre == 'CONFRONTA'){
                 body += `<td>${datos[x].turnado}</td>`
            }
            else if(datos[x].nombre == 'IFA'){
             body += `<td>${datos[x].turnado}</td>`
            }
            else{
             body += `<td>No Asignado</td>`
            }
        }
        body += `</tr>`
    }
    else{
        body = `<tr><td>No Asignado</td><td>No Asignado</td><td>No Asignado</td></tr>`
    }
    let template = `
    <table class="datosTurnado">
      <thead>
        <tr>
          <th>Irac</th>
          <th>Confronta</th>
          <th>Ifa</th>
        </tr>
      </thead>
      <tbody>:datos</tbody>
    </table>`
    let res = template.replace(':datos',body)
    return res
    
 }

 function remitentes(template){
    $.alert({
        title: 'Seleccione Remitente',
        theme:'modern',
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    let val = $('input:radio[name=remitente]:checked').val()
                    $('input#idRemitente').val(val)

                }},
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                action:function(){
                   
                }}}})
 }



 function firmas(template){
    $.alert({
        title: 'Personal que firmas',
        theme:'modern',
        content:template,
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

module.exports = modals