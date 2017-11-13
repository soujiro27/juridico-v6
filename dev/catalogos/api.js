const $ = require('jquery')

const api = {
    subDocumentos,
    subDocumentosAuditoria,
    getAuditoria,
    getTurnadoAuditoria,
    remitentes,
    subDocumentosNoAuditoria,
    volanteDocumentos,
    firmas,
    doctosTextos,
    remitentesPlantillas,
    closeVolante

}

function subDocumentos(val){
     let datos = new Promise(resolve =>{
            $.get({
                url:`/SIA/juridico/datos/subDocumentos`,
                data:{dato:val},
                success: function(json){
                    resolve(JSON.parse(json))
                }
            })
        })
        return datos
}

function remitentes(val){
    let datos = new Promise(resolve =>{
           $.get({
               url:`/SIA/juridico/datos/remitentes`,
               success: function(json){
                   resolve(JSON.parse(json))
               }
           })
       })
       return datos
}

function subDocumentosAuditoria(val){
    let datos = new Promise(resolve =>{
           $.get({
               url:`/SIA/juridico/datos/subDocumentosAuditoria`,
               data:{dato:val},
               success: function(json){
                   resolve(JSON.parse(json))
               }
           })
       })
       return datos
}

function subDocumentosNoAuditoria(val){
    let datos = new Promise(resolve =>{
           $.get({
               url:`/SIA/juridico/datos/subDocumentosNoAuditoria`,
               data:{dato:val},
               success: function(json){
                   resolve(JSON.parse(json))
               }
           })
       })
       return datos
}

function getAuditoria(data){
    let datos = new Promise(resolve =>{
           $.get({
               url:`/SIA/juridico/datos/auditoria`,
               data:data,
               success: function(json){
                   resolve(JSON.parse(json))
               }
           })
       })
       return datos
}



function getTurnadoAuditoria(data){
    let datos = new Promise(resolve =>{
           $.get({
               url:`/SIA/juridico/datos/turnadoAuditoria`,
               data:data,
               success: function(json){
                   resolve(JSON.parse(json))
               }
           })
       })
       return datos
}

function volanteDocumentos(data){
    let datos = new Promise(resolve =>{
        $.get({
            url:`/SIA/juridico/datos/volanteByFolio`,
            data:data,
            success: function(json){
                resolve(JSON.parse(json))
            }
        })
    })
    return datos
}

function firmas(){
    let datos = new Promise(resolve =>{
        $.get({
            url:`/SIA/juridico/datos/firmas`,
            success: function(json){
                resolve(JSON.parse(json))
            }
        })
    })
    return datos
}

function doctosTextos(){
    let datos = new Promise(resolve =>{
        $.get({
            url:`/SIA/juridico/datos/doctosTextos`,
            success: function(json){
                resolve(JSON.parse(json))
            }
        })
    })
    return datos
}

function remitentesPlantillas(data){
    let datos = new Promise(resolve =>{
        $.get({
            url:`/SIA/juridico/datos/remitentesPlantilla`,
            data:data,
            success: function(json){
                resolve(JSON.parse(json))
            }
        })
    })
    return datos
}


function closeVolante(data){
    let datos = new Promise(resolve =>{
        $.post({
            url:`/SIA/juridico/datos/closeVolante`,
            data:data,
            success: function(json){
                location.href = "/SIA/juridico/"+data['ruta'];
            }
        })
    })
    return datos
}


module.exports = api