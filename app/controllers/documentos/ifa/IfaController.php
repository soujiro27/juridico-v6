<?php
namespace App\Controllers\Documentos\Ifa;

use App\Controllers\Catalogs\BaseController;
use App\Models\DocumentosSiglas;
use App\Models\ObservacionesDoctosJuridico;
use App\Models\Volantes;
use App\Models\PuestosJuridico;
use App\Models\VolantesDocumentos;

class IfaController extends BaseController {
    public function getIndex()
    {
        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}


        $iracs = Volantes::select('sia_Volantes.idVolante','sia_Volantes.folio',
            'sia_Volantes.numDocumento','sia_Volantes.idRemitente','sia_Volantes.fRecepcion','sia_Volantes.asunto'
            ,'c.nombre as caracter','a.nombre as accion','audi.clave','sia_Volantes.extemporaneo')
            ->join('sia_catCaracteres as c','c.idCaracter','=','sia_Volantes.idCaracter')
            ->join('sia_CatAcciones as a','a.idAccion','=','sia_Volantes.idAccion')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_Volantes.idVolante')
            ->join('sia_auditorias as audi','audi.idAuditoria','=','vd.cveAuditoria')
            ->join( 'sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->where('sub.nombre','=','IFA')
            ->where('sia_volantes.idTurnado','=',"$areaUsuario")
            ->get();


        return $this->render('/ifa/ifa.twig',['iracs' => $iracs,'sesiones'=> $_SESSION]);
    }

    public function getObservaciones($idVolante)
    {
        $observaciones = ObservacionesDoctosJuridico::all()->where('idVolante','=',"$idVolante");
        return $this->render('/ifa/Observaciones.twig',['observaciones' => $observaciones,'idVolante' => $idVolante]);
    }


    public function getCreateObservacion($idVolante) {

        $volantesDoc = VolantesDocumentos::all()->where('idVolante','=',"$idVolante");
        foreach ($volantesDoc as $volantes) {$cveAuditoria=$volantes['cveAuditoria']; $idSubTipoDocumento = $volantes['idSubTipoDocumento']; }

        return $this->render('/irac/insert-Observaciones.twig',['sesiones'=> $_SESSION,
            'cveAuditoria' =>$cveAuditoria,
            'idSubTipoDocumento' => $idSubTipoDocumento,
            'idVolante' => $idVolante]);
    }

    public function observacionesCreate($post) {
        $fecha=strftime( "%Y-%d-%m", time() );
        $acciones = new ObservacionesDoctosJuridico([
            'idVolante' => $post['idVolante'],
            'idSubTipoDocumento' => $post['idSubTipoDocumento'],
            'cveAuditoria' => $post['cveAuditoria'],
            'pagina' => $post['pagina'],
            'parrafo' => $post['parrafo'],
            'observacion' => $post['observacion'],
            'idDocumentoTexto' => $post['idDocumentoTexto'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => $fecha
        ]);
        $acciones->save();
        echo $this->getIndex();

    }

    public function getUpdateObservacion($id,$err) {

        $observacion = ObservacionesDoctosJuridico::where('idObservacionDoctoJuridico','=',"$id")->first();
        return $this->render('/ifa/update-observaciones.twig',[
            'sesiones'=> $_SESSION,
            'observacion'=> $observacion,
            'error' => $err
        ]);
    }

    public function observacionUpdate($post) {

        $fecha=strftime( "%Y-%d-%m", time() );
        ObservacionesDoctosJuridico::where('idObservacionDoctoJuridico',$post['idObservacionDoctoJuridico'])
            ->update(['pagina' => $post['pagina'],
                'parrafo' => $post['parrafo'],
                'observacion' => $post['observacion'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'idDocumentoTexto' => $post['idDocumentoTexto'],
                'fModificacion' => $fecha,
                'estatus' => $post['estatus']
            ]);
        echo $this->getIndex();


    }

    public function  getCreateCedula($idVolante){
        $datosCedula = $this->duplicate($idVolante);


        $idUsuario = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$idUsuario");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}

        $volantesDocumentos = VolantesDocumentos::all()->where('idVolante','=',"$idVolante");
        foreach ($volantesDocumentos as $vd){$idSubTipoDocumento=$vd['idSubTipoDocumento'];}

        $puestos = PuestosJuridico::all()->where('idArea','=',"$areaUsuario")
            ->where('titular','=','NO');

        if(empty($datosCedula)){
            return $this->render('/ifa/insert-Cedula.twig',['sesiones'=> $_SESSION,
                'puestos' => $puestos,
                'idVolante' => $idVolante,
                'idSubTipoDocumento' => $idSubTipoDocumento]);
        }
        else{
            return $this->render('/ifa/update-cedula.twig',['datosCedula' => $datosCedula]);
        }
    }

    public function duplicate($id) {
        $duplicate = DocumentosSiglas::where('idVolante','=',"$id")->first();
        return $duplicate;
    }

    public function cedulaCreate($post){
        $fecha=strftime( "%Y-%d-%m", time() );
        $cedula = new DocumentosSiglas([
            'idVolante' => $post['idVolante'],
            'idSubTipoDocumento' => $post['idSubTipoDocumento'],
            'siglas' => $post['siglas'],
            'idPuestosJuridico' => $post['idPuestosJuridico'],
            'fOficio' => $post['fOficio'],
            'numFolio' => $post['numFolio'],
            'idDocumentoTexto' => $post['idDocumentoTexto'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => $fecha
        ]);
        $cedula->save();
        echo $this->getIndex();

    }

    public function cedulaUpdate($post){
        $fecha=strftime( "%Y-%d-%m", time() );
        DocumentosSiglas::where('idDocumentoSiglas',$post['idDocumentoSiglas'])
            ->update(['siglas' => $post['siglas'],
                'fOficio' => $post['fOficio'],
                'numFolio' => $post['numFolio'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => $fecha,
                'idPuestosJuridico' => $post['idPuestosJuridico'],
                'idDocumentoTexto' => $post['idDocumentoTexto']
            ]);
        echo $this->getIndex();
    }

}