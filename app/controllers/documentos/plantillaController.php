<?php
namespace App\Controllers\Documentos;


use App\Controllers\BaseController;
use Carbon\Carbon;
use App\Models\Acciones;
use App\Models\Turnos;
use App\Models\Areas;
use App\Models\PlantillasJuridico;
use App\Models\SubTiposDocumentos;
use App\Models\TiposDocumentos;
use App\Models\Volantes;
use \App\Models\Caracteres;
use App\Models\VolantesDocumentos;
use App\Models\PuestosJuridico;
use App\Models\Remitentes;

class PlantillaController extends BaseController {
    public function getIndex() {
         $this->permisoModulos('DOCUMENTOS-DIVERSOS');
        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}
         if(empty($areaUsuario)){
            $app = \Slim\Slim::getInstance();
            $app->redirect('/SIA');
        }

        $volantes = VolantesDocumentos::select('v.idVolante','v.folio','v.subfolio','v.numDocumento','v.idRemitente'
            ,'v.idTurnado','v.fRecepcion','v.extemporaneo','sub.nombre','t.estadoProceso','v.estatus')
            ->join('sia_Volantes as v','v.idVolante','=','sia_volantesDocumentos.idVolante')
            ->join('sia_turnosJuridico as t','t.idVolante','=','v.idVolante'  )
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','sia_volantesDocumentos.idSubTipoDocumento')
            ->where('sub.auditoria','NO')
            ->where('v.idTurnado','=',"$areaUsuario")
            ->get();
        return $this->render('/plantillas/tabla.twig',[
            'volantes' => $volantes,
            'sesiones'=> $_SESSION,
            'modulo' => 'Documentos Diversos',
            'notifica' => $this->getNotificaciones(),
            ]);
    }


    public function getCreate($idVolante) {
        $this->permisoModulos('DOCUMENTOS-DIVERSOS');
        $plantillas = PlantillasJuridico::where('idVolante','=',"$idVolante")->get();
        foreach ($plantillas as $plantilla){$vacio = $plantilla['idPlantillaJuridico'];}
        foreach ($plantillas as $plantilla){$copías = $plantilla['copias'];}

        $remitentes = Volantes::where('idVolante','=',"$idVolante")->get();
        foreach ($remitentes as $remitente){
            $idRemitente = $remitente['idRemitenteJuridico'];
            $tipoDocto = $remitente['idTipoDocto'];
            ;}

        if(empty($vacio)){

        $id = $_SESSION['idEmpleado'];
            return $this->render('plantillas/insert-plantillas.twig',[
                'sesiones' => $_SESSION,
                'idVolante' => $idVolante,
                'idRemitente' => $idRemitente,
                'tipo' => $tipoDocto,
                  'modulo' => 'Documentos Diversos',
            'notifica' => $this->getNotificaciones(),
            ]);
        }else {
            $datos = explode(",",$copías);
            $internos = '';
            $externos = '';
            foreach ($datos as $llave => $valor){
                $id = $valor;
                $tipo = Remitentes::where('idRemitenteJuridico','=',"$id")->get();
                if($tipo[0]['tipoRemitente'] == 'I'){
                    $internos .= $tipo[0]['idRemitenteJuridico'] . ',';
                }else{
                    $externos .= $tipo[0]['idRemitenteJuridico'] . ',';
                }
            }

            $idInternos = substr($internos,0,-1);
            $idExternos = substr($externos,0,-1);


           $close = $this->verificaVolante($idVolante);
           if(!$close){
               $err = 'El volante fue CERRADO no puede Modificarse';
           }else {
               $err = false;
           }
           return $this->render('/plantillas/update-plantillas.twig',[
                'sesiones' => $_SESSION,
                'idVolante' => $idVolante,
                'plantillas' => $plantillas,
                'internos' => $internos,
                'externos' => $externos,
                'close' => $close,
                'tipo' => $tipoDocto,
                'err' => $err,
                  'modulo' => 'Documentos Diversos',
            'notifica' => $this->getNotificaciones(),
            ]);
        }

    }


    public function create($post,$app) {
        $copias = $post['internos'] . $post['externos'];
        $copias = substr($copias,0,-1);
        $datos = array(
            'idVolante' =>$post['idVolante'],
            'numFolio' => $post['numFolio'],
            'fOficio' => $post['fOficio'],
            'idRemitente' => $post['idRemitente'],
            'texto' => $post['texto'],
            'siglas' => $post['siglas'],
            'copias' => $copias,
            'espacios' => $post['espacios'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => Carbon::now('America/Mexico_City')->format('Y-m-d')
        );
        if(isset($post['asunto'])){
           $datos['asunto'] = $post['asunto'];
        }else{
            $datos['idPuestoJuridico'] = $post['idPuestoJuridico'];
        }

        $plantillas = new PlantillasJuridico($datos);
        $plantillas->save();
        $app->redirect('/SIA/juridico/DocumentosDiversos');



    }

    public function update($post,$app) {
        $copias = $post['internos'] . $post['externos'];
        $copias = substr($copias,0,-1);
        $datos = array(
            'numFolio' => $post['numFolio'],
            'fOficio' => $post['fOficio'],
            'idRemitente' => $post['idRemitente'],
            'texto' => $post['texto'],
            'siglas' => $post['siglas'],
            'copias' => $copias,
            'espacios' => $post['espacios'],
            'usrModificacion' => $_SESSION['idUsuario'],
            'fModificacion' => Carbon::now('America/Mexico_City')->format('Y-m-d')
        );

        if(isset($post['asunto'])){
            $datos['asunto'] = $post['asunto'];
        }else{
            $datos['idPuestoJuridico'] = $post['idPuestoJuridico'];
        }

        PlantillasJuridico::where('idPlantillaJuridico',$post['idPlantillaJuridico'])->update($datos);
        $app->redirect('/SIA/juridico/DocumentosDiversos');


    }


    public function verificaVolante($id){

        $datos = Turnos::where('idVolante','=',$id)->get();
        $turno =  $datos[0]['estadoProceso'];
        if($turno == 'CERRADO' ){
            return false;
        }else{
            return true;
        }
    }
}