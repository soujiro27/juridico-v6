require('babelify-es6-polyfill')
window.CKEDITOR_BASEPATH = '/SIA/juridico/node_modules/ckeditor/'
const $ = require('jquery')
const utils = require('./utils')
const volantes = require('./volantes') 
const documentos = require('./documentosGral')
const plantilla = require('./plantillas')
const irac = require('./irac')
utils.update()
utils.cancel()
utils.getSub()
utils.ckeditorLoad()
$('input.fechaInput').datepicker({ dateFormat: "yy-mm-dd" });

/*--------------volantes------------*/

volantes.getSubDocumentos()
volantes.nota()
volantes.auditoria()
volantes.remitentes()
volantes.getSubDocumentosSinAuditoria()

/*------------Documentos upload-------*/

documentos.volantesByFolio()
documentos.download()


/*--------Plantillas------------------*/

plantilla.getInsert()




/*----------IRAC--------------------*/

irac.panelObservaciones()
irac.updateObservacion()
irac.firmas()