const $ = require('jquery')

const api = {
    subDocumentos,
    getAuditoria,
    getTurnadoAuditoria
}

function subDocumentos(val){
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


module.exports = api