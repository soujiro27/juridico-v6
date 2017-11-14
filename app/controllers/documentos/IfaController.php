<?php
namespace App\Controllers\Documentos;

use App\Controllers\BaseController;
use App\Models\DocumentosSiglas;
use App\Models\ObservacionesDoctosJuridico;
use App\Models\Volantes;
use App\Models\PuestosJuridico;
use App\Models\VolantesDocumentos;
use Carbon\Carbon;

class IfaController extends BaseController {
    public function getIndex()
    {
        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}


        $iracs = Volantes::select('sia_Volantes.idVolante','sia_Volantes.folio',
            'sia_Volantes.numDocumento','sia_Volantes.idRemitente','sia_Volantes.fRecepcion','sia_Volantes.asunto'
            ,'c.nombre as caracter','a.nombre as accion','audi.clave','sia_Volantes.extemporaneo','tj.estadoProceso')
            ->join('sia_catCaracteres as c','c.idCaracter','=','sia_Volantes.idCaracter')
            ->join('sia_CatAcciones as a','a.idAccion','=','sia_Volantes.idAccion')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_Volantes.idVolante')
            ->join('sia_auditorias as audi','audi.idAuditoria','=','vd.cveAuditoria')
            ->join('sia_turnosJuridico as tj','tj.idVolante','sia_Volantes.idVolante')
            ->join( 'sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->where('sub.nombre','=','IFA')
            ->where('sia_volantes.idTurnado','=',"$areaUsuario")
            ->get();


        return $this->render('/ifa/tabla-ifa.twig',['iracs' => $iracs,'sesiones'=> $_SESSION]);
    }

    public function getObservaciones($idVolante)
    {

        if($this->verificaVolante($idVolante)){$err = false;}else{$err = 'El Ifa Ha sido Cerrado';}
        $observaciones = ObservacionesDoctosJuridico::all()->where('idVolante','=',"$idVolante");
        return $this->render('/ifa/Observaciones.twig',[
            'observaciones' => $observaciones,
            'idVolante' => $idVolante,
            'close' => $this->verificaVolante($idVolante),
            'err' => $err
        ]);
    }


    public function getCreateObservacion($idVolante) {

        $volantesDoc = VolantesDocumentos::all()->where('idVolante','=',"$idVolante");
        foreach ($volantesDoc as $volantes) {$cveAuditoria=$volantes['cveAuditoria']; $idSubTipoDocumento = $volantes['idSubTipoDocumento']; }

        return $this->render('/ifa/insert-Observaciones.twig',['sesiones'=> $_SESSION,
            'cveAuditoria' =>$cveAuditoria,
            'idSubTipoDocumento' => $idSubTipoDocumento,
            'idVolante' => $idVolante]);
    }

    public function observacionesCreate($post,$app) {

        $acciones = new ObservacionesDoctosJuridico([
            'idVolante' => $post['idVolante'],
            'idSubTipoDocumento' => $post['idSubTipoDocumento'],
            'cveAuditoria' => $post['cveAuditoria'],
            'pagina' => $post['pagina'],
            'parrafo' => $post['parrafo'],
            'observacion' => $post['observacion'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => Carbon::now('America/Mexico_City')->format('Y-m-d')
        ]);
        $acciones->save();
       $app->redirect('/SIA/juridico/Ifa');

    }

    public function getUpdateObservacion($id,$err) {

        $observacion = ObservacionesDoctosJuridico::where('idObservacionDoctoJuridico','=',"$id")->first();
        return $this->render('/ifa/update-observaciones.twig',[
            'sesiones'=> $_SESSION,
            'observacion'=> $observacion,
            'error' => $err
        ]);
    }

    public function observacionUpdate($post,$app) {


        ObservacionesDoctosJuridico::where('idObservacionDoctoJuridico',$post['idObservacionDoctoJuridico'])
            ->update(['pagina' => $post['pagina'],
                'parrafo' => $post['parrafo'],
                'observacion' => $post['observacion'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => Carbon::now('America/Mexico_City')->format('Y-m-d'),
                'estatus' => $post['estatus']
            ]);
        $app->redirect('/SIA/juridico/Ifa');


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

    public function cedulaCreate($post,$app){

        $cedula = new DocumentosSiglas([
            'idVolante' => $post['idVolante'],
            'idSubTipoDocumento' => $post['idSubTipoDocumento'],
            'siglas' => $post['siglas'],
            'idPuestosJuridico' => $post['idPuestosJuridico'],
            'fOficio' => $post['fOficio'],
            'numFolio' => $post['numFolio'],
            'idDocumentoTexto' => $post['idDocumentoTexto'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s')
        ]);
        $cedula->save();
        $app->redirect('/SIA/juridico/Ifa');

    }

    public function cedulaUpdate($post,$app){
        $fecha=strftime( "%Y-%d-%m", time() );
        DocumentosSiglas::where('idDocumentoSiglas',$post['idDocumentoSiglas'])
            ->update(['siglas' => $post['siglas'],
                'fOficio' => $post['fOficio'],
                'numFolio' => $post['numFolio'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s'),
                'idPuestosJuridico' => $post['idPuestosJuridico'],
                'idDocumentoTexto' => $post['idDocumentoTexto']
            ]);
        $app->redirect('/SIA/juridico/Ifa');
    }

}