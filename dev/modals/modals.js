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
    dictamen,
    TableDatosAuditoria,
    tableTurnados,
    remitentes,
    firmas,
    promocion,
    internos,
    externos,
    closeVolante,
    puestos
}

function nota(){
    $.alert({
        title: '¿Contiene NOTA INFORMATIVA?',
        theme:'modern',
        draggable: true,
        dragWindowBorder: false,
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
                }}},
                columnClass: 'col-md-12'
            })
}


function dictamen(){
    $.alert({
        title: 'Seleccione la Cuenta Publica',
        theme:'modern',
        draggable: true,
        dragWindowBorder: false,
        content:`<select id="cta-publica">
            <option value="">Seleccione Cuenta Publica </option>
            <option value="CTA-2015">CTA-2015</option>
            <option value="CTA-2016">CTA-2016</option>
        `,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'ACEPTAR',
                action:function(){
                    let cta = $('select#cta-publica :selected').val()
                    $('input#cta-publica').val(cta)
                }},
            cancel:{
                btnClass:'btn-danger',
                text:'CANCELAR',
                action:function(){
                   
                }}},
                columnClass: 'col-md-16'
            })
}



function auditoria(){
    let self = this
    let cta = $('input#cta-publica').val()
    let year = cta.substring(6)
    let anio = cta.substring(4)
        $.confirm({
            title: 'Seleccione el Numero de Auditoria',
            theme:'modern',
            draggable: true,
            dragWindowBorder: false,
            columnClass: 'col-md-12',
            content: `<div class="auditoria-container">
            <div class="auditoria">
              <div class="cuenta">
                <p class="cuenta">CUENTA PUBLICA ${anio}</p>
              </div>
              <div class="search"><span>ASCM/</span>
                <input id="auditoria" type="text" name="auditoria"/><span>/${year}</span>
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
                let cta = $('input#cta-publica').val()
                let year = cta.substring(6)
                    $('input#auditoria').keyup(function(){
                        let cve = `ASCM/${$(this).val()}/${year}`
                        if(cve != ''){

                            let datosAuditoria = co(function *(){
                                let datos = yield api.getAuditoria({clave:cve})
                                let turnado = yield api.getTurnadoAuditoria({cveAuditoria:datos[0].idAuditoria})
                                
                                let table = self.TableDatosAuditoria(datos)
                                $('div.datosAuditoria').html(table)
                                
                                let tableTurnado = self.tableTurnados(turnado)
                                $('div.asignacion').html(tableTurnado)
                                
                                $('span#auditoria').text(cve)
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
    <table class="datosAuditoria table table-hover">
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
        body = `<tr>
        <td>No Asignado</td>
        <td>No Asignado</td>
        <td>No Asignado</td>
        </tr>`
    }
    let template = `
    <table class="datosTurnado table table-hover">
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
        draggable: true,
        dragWindowBorder: false,
        columnClass: 'col-md-12',
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    let val = $('input:radio[name=remitente]:checked').val()
                    let id = $('input:radio[name=remitente]:checked').data('id')
                    $('input#idRemitente').val(val)
                    $('input#idRemitenteJuridico').val(id)
                    let nombre = $('input:radio[name=remitente]:checked').data('nombre')
                    let puesto = $('input:radio[name=remitente]:checked').data('puesto')
                    $('input#nombreRemitente').val(nombre);
                    $('input#puestoRemitente').val(puesto)

                }},
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                action:function(){
                   
                }},
                
            },
            onOpenBefore:function(){
                $('div.jconfirm-holder').addClass('firmasModal')
            }
        })
 }



 function firmas(template){
    $.alert({
        title: 'Personal que Firma la Cedula',
        theme:'modern',
        draggable: true,
        dragWindowBorder: false,
        columnClass: 'col-md-12',
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    var categorias = ''
                    $("input[name='firmas']:checked").each(function() {
                        categorias += $(this).val() + ','
                    });
                    $('input#idPuestosJuridico').val(categorias)
                }
               },
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                }
            },
            onOpenBefore:function(){
                $('div.jconfirm-holder').addClass('firmasModal')
            
                let val = $('input#idPuestosJuridico').val()
                if(val){
                    var puestosArray = val.split(',')
                    for(let x in puestosArray){
                        $(`input[value="${puestosArray[x]}"]#firmas`).prop('checked',true)
                    }
                }                
            }
              
        })
}



function promocion(template){
    $.alert({
        title: 'Texto Promocion de Acciones',
        theme:'modern',
        columnClass: 'col-md-12',
        draggable: true,
        dragWindowBorder: false,
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    let val = $('input:radio[name=texto]:checked').val()
                    $('input#idDocumentoTexto').val(val)

                }
               },
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                }
            },
            onOpenBefore:function(){
            $('div.jconfirm-holder').addClass('firmasModal')
               let id = $('input#idDocumentoTexto').val()
               $(`input[value="${id}"]#textoPromocion`).prop('checked',true)
                
            }
        })
}




function internos(template){
    $.alert({
        title: 'Personal que Firma',
        theme:'modern',
        draggable: true,
        dragWindowBorder: false,
        columnClass: 'col-md-12',
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    var categorias = ''
                    $("input[name='ccpInternos']#ccpInternos:checked").each(function() {
                        categorias += $(this).val() + ','
                    });
                    $('input#internos').val(categorias)
                    
                }
               },
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                }
            },
            onOpenBefore:function(){
                $('div.jconfirm-box-container').removeClass('col-md-4')
                $('div.jconfirm-box-container').addClass('col-md-12')
                let val = $('input#internos').val()
                if(val){
                    var puestosArray = val.split(',')
                    for(let x in puestosArray){
                        $(`input[value="${puestosArray[x]}"]#ccpInternos`).prop('checked',true)
                    }
                }
                
            }
        })
}




function externos(template){
    $.alert({
        title: 'Personal que Firma',
        theme:'modern',
        draggable: true,
        dragWindowBorder: false,
        columnClass: 'col-md-12',
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    var categorias = ''
                    $("input[name='ccpExternos']#ccpExternos:checked").each(function() {
                        categorias += $(this).val() + ','
                    });
                    $('input#externos').val(categorias)
                    
                }
               },
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                }
            },
            onOpenBefore:function(){
                $('div.jconfirm-box-container').removeClass('col-md-4')
                $('div.jconfirm-box-container').addClass('col-md-12')
                let val = $('input#externos').val()
                if(val){
                    var puestosArray = val.split(',')
                    for(let x in puestosArray){
                        $(`input[value="${puestosArray[x]}"]#ccpExternos`).prop('checked',true)
                    }
                }
                
            }
        })
}


function closeVolante(idVolante,ruta){
    $.confirm({
        title: 'Cerrar Volante!',
        columnClass: 'col-md-12',
        draggable: true,
        dragWindowBorder: false,
        content: '¿Desea Cerrar el Volante?',
        buttons: {
            confirm:{
                text: 'Aceptar',
                btnClass: 'btn-info',
                action:function () {
                        api.closeVolante({
                            idVolante:idVolante,
                            ruta:ruta
                        })
                  }
            },
            cancel:{
                text: 'Cancelar',
                btnClass:'btn-danger'
            }
        }
    });
                                   
}

function puestos(template){
    $.alert({
        title: 'Seleccione Puesto',
        theme:'modern',
        draggable: true,
        dragWindowBorder: false,
        columnClass: 'col-md-12',
        content:template,
        buttons:{
            confirm:{
                btnClass:'btn-primary',
                text: 'Aceptar',
                action:function(){
                    let val = $('input:radio[name=puesto]:checked').val()
                    let id = $('input:radio[name=remitente]:checked').data('id')
                    $('input#puesto').val(val)
                   /* $('input#idRemitenteJuridico').val(id)
                    let nombre = $('input:radio[name=remitente]:checked').data('nombre')
                    let puesto = $('input:radio[name=remitente]:checked').data('puesto')
                    $('input#nombreRemitente').val(nombre);
                    $('input#puestoRemitente').val(puesto)
                    */

                }},
            cancel:{
                btnClass:'btn-danger',
                text:'Cancelar',
                action:function(){
                   
                }},
                
            }
        })
 }


module.exports = modals