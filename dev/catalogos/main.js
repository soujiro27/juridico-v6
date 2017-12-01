require('babelify-es6-polyfill')
window.CKEDITOR_BASEPATH = '/SIA/juridico/node_modules/ckeditor/'
const $ = require('jquery')

const utils = require('./utils')
const volantes = require('./volantes') 
const documentos = require('./documentosGral')
const plantilla = require('./plantillas')
const irac = require('./irac')
const confronta = require('./confronta')
const ifa = require('./ifa')


utils.update()
utils.cancel()
utils.getSub()
utils.ckeditorLoad()
utils.logout()
$('input.fechaInput').datepicker({ dateFormat: "yy-mm-dd" });


/*--------------volantes------------*/

volantes.getSubDocumentos()
volantes.nota()
volantes.auditoria()
volantes.remitentes()
volantes.getSubDocumentosSinAuditoria()
volantes.order()
volantes.cerrarVolante()

/*------------Documentos upload-------*/

documentos.volantesByFolio()
documentos.download()
documentos.download_sub()


/*--------Plantillas------------------*/

plantilla.getInsert()
plantilla.internos()
plantilla.externos()
plantilla.puestos()



/*----------IRAC--------------------*/

irac.panelObservaciones()
irac.updateObservacion()
irac.firmas()



/*--------------confrontas ----------*/

confronta.cedula()


/*-------------IFa-------------------*/

ifa.panelObservaciones()
ifa.updateObservacion()
ifa.doctosTexto()