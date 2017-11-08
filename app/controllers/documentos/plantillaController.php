<?php
namespace App\Controllers\Documentos;

use App\Controllers\Catalogs\AccionesController;
use App\Controllers\BaseController;
use App\Models\Acciones;
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

        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}

        $volantes = VolantesDocumentos::select('v.idVolante','v.folio','v.subfolio','v.numDocumento','v.idRemitente'
            ,'v.idTurnado','v.fRecepcion','v.extemporaneo','sub.nombre','t.estadoProceso','v.estatus')
            ->join('sia_Volantes as v','v.idVolante','=','sia_volantesDocumentos.idVolante')
            ->join('sia_turnosJuridico as t','t.idVolante','=','v.idVolante'  )
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','sia_volantesDocumentos.idSubTipoDocumento')
            ->where('sub.auditoria','NO')
            ->where('v.idTurnado','=',"$areaUsuario")
            ->get();
        return $this->render('/plantillas/tabla.twig',
            ['volantes' => $volantes,'sesiones'=> $_SESSION]);
    }


    public function getCreate($idVolante) {
        $plantillas = PlantillasJuridico::where('idVolante','=',"$idVolante")->get();
        foreach ($plantillas as $plantilla){$vacio = $plantilla['idPlantillaJuridico'];}
        foreach ($plantillas as $plantilla){$copías = $plantilla['copias'];}

        $remitentes = Volantes::where('idVolante','=',"$idVolante")->get();
        foreach ($remitentes as $remitente){$idRemitente = $remitente['idRemitente'];}
        if(empty($vacio)){
            return $this->render('plantillas/insert-plantillas.twig',[
                'sesiones' => $_SESSION,
                'idVolante' => $idVolante,
                'idRemitente' => $idRemitente
            ]);
        }else {
            $datos = explode(",",$copías);
            foreach ($datos as $llave => $valor){
                $id = $valor;
                $tipo = Remitentes::where('idRemitenteJuridico','=',"$id")->get();

            }
            var_dump($tipo);
           /* return $this->render('/plantillas/update-plantillas.twig',[
                'sesiones' => $_SESSION,
                'idVolante' => $idVolante,
                'plantillas' => $plantillas
            ]);*/
        }

    }


    public function create($post) {
        $fecha=strftime( "%Y-%d-%m", time() );
        $copias = $post['internos'] . $post['externos'];
        $copias = substr($copias,0,-1);
        $plantillas = new PlantillasJuridico([
            'idVolante' =>$post['idVolante'],
            'numFolio' => $post['numFolio'],
            'asunto' => $post['asunto'],
            'fOficio' => $post['fOficio'],
            'idRemitente' => $post['idRemitente'],
            'texto' => $post['texto'],
            'siglas' => $post['siglas'],
            'copias' => $copias,
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => $fecha
        ]);
        $plantillas->save();
        echo $this->getIndex();



    }

    public function update($post) {


        $fecha=strftime( "%Y-%d-%m", time() );
        PlantillasJuridico::where('idPlantillaJuridico',$post['idPlantillaJuridico'])->update([
            'numFolio' => $post['numFolio'],
            'asunto' => $post['asunto'],
            'fOficio' => $post['fOficio'],
            'idRemitente' => $post['idRemitente'],
            'texto' => $post['texto'],
            'siglas' => $post['siglas'],
            'copias' => $post['copias'],
            'usrModificacion' => $_SESSION['idUsuario'],
            'fModificacion' => $fecha,

        ]);
        echo $this->getIndex();


    }

/*
    public function getUpdate($id,$err) {
        $duplicate = false;
        $volantes = Volantes::where('idVolante',$id)->first();

        $caracteres = Caracteres::where('estatus','ACTIVO')->get();
        $acciones = Acciones::where('estatus','ACTIVO')->get();
        $turnados  = Areas::where('idAreaSuperior','DGAJ')->where('estatus','ACTIVO')->get();
        $turnadoDireccion = array ('idArea'=>'DGAJ','nombre' => 'DIRECCIÓN GENERAL DE ASUNTOS JURIDICOS');


        return $this->render('/volantesDiversos/update-volantesDiversos.twig',[
            'sesiones'=> $_SESSION,
            'volantes'=> $volantes,
            'caracteres' => $caracteres,
            'acciones' => $acciones,
            'turnados' => $turnados,
            'direccionGral' => $turnadoDireccion,
            'error' => $err
        ]);
    }



    public function volantesUpdate($post) {


        $fecha=strftime( "%Y-%d-%m", time() );
        Volantes::where('idVolante',$post['idVolante'])->update([
            'numDocumento' => $post['numDocumento'],
            'anexos' => $post['anexos'],
            'fDocumento' => $post['fDocumento'],
            'fRecepcion' => $post['fRecepcion'],
            'hRecepcion' => $post['hRecepcion'],
            'asunto' => $post['asunto'],
            'idCaracter' => $post['idCaracter'],
            'idTurnado' => $post['idTurnado'],
            'idAccion' => $post['idAccion'],
            'usrModificacion' => $_SESSION['idUsuario'],
            'fModificacion' => $fecha,
            'estatus' => $post['estatus']
        ]);
        echo $this->getIndex();


    }

    public function duplicate($post) {
        $duplicate = Caracteres::where('siglas',$post['siglas'])->where('nombre' ,$post['nombre'])->where('estatus',$post['estatus'])->first();
        return $duplicate;
    }
*/
}