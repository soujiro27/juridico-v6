const $ = require('jquery')

const api = {
    subDocumentos,
    
}

function subDocumentos(val){
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


module.exports = api