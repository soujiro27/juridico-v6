<?php
namespace App\Controllers\Documentos;


use App\Controllers\BaseController;
use App\Controllers\NotificaController;
use Carbon\Carbon;
use App\Models\Acciones;
use App\Models\Areas;
use App\Models\PuestosJuridico;
use App\Models\SubTiposDocumentos;
use App\Models\TiposDocumentos;
use App\Models\Volantes;
use \App\Models\Caracteres;
use App\Models\VolantesDocumentos;
use App\Models\Notificaciones;
use App\Models\Turnos;

class VolantesController extends BaseController {
    public function getIndex($app) {
        if(empty($app->request->get()))
        {
            $campo = 'folio';
            $tipo = 'desc';
        }else{
            $get = $app->request->get();
            $campo = $get['campo'];
            $tipo = $get['tipo'];
        }

        $volantes = VolantesDocumentos::select('v.idVolante','v.folio','v.subfolio','v.numDocumento','v.idRemitente'
            ,'v.idTurnado','v.fRecepcion','v.extemporaneo','a.clave','sub.nombre','t.estadoProceso','v.estatus')
            ->join('sia_Volantes as v','v.idVolante','=','sia_volantesDocumentos.idVolante')
            ->join('sia_turnosJuridico as t','t.idVolante','=','v.idVolante'  )
            ->join('sia_auditorias as a','a.idAuditoria','=','sia_volantesDocumentos.cveAuditoria')
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','sia_volantesDocumentos.idSubTipoDocumento')
            ->where('sub.auditoria','SI')
            ->orderBy("$campo","$tipo")
            ->get();
        return $this->render('/volantes/tabla.twig',['volantes' => $volantes,'sesiones'=> $_SESSION]);
    }
    public function getCreate($err) {

        $documentos = TiposDocumentos::where('estatus','ACTIVO')->where('tipo','JURIDICO')->get();
        $caracteres = Caracteres::where('estatus','ACTIVO')->get();
        $acciones = Acciones::where('estatus','ACTIVO')->get();
        $turnados  = Areas::where('idAreaSuperior','DGAJ')->where('estatus','ACTIVO')->get();
        $turnadoDireccion = array ('idArea'=>'DGAJ','nombre' => 'DIRECCIÓN GENERAL DE ASUNTOS JURIDICOS');

        $subTipos = SubTiposDocumentos::where('estatus','ACTIVO')->get(); //cambiar por evento js

        return $this->render('/volantes/insert.twig',[
            'sesiones' => $_SESSION,
            'documentos' => $documentos,
            'caracteres' => $caracteres,
            'acciones' => $acciones,
            'turnados' => $turnados,
            'direccionGral' => $turnadoDireccion,
            'subtipos' => $subTipos,
            'err' => $err
        ]);
    }


    public function getUpdate($id,$err) {

        $close = $this->verificaVolante($id);
        $volantes = Volantes::where('idVolante',$id)->first();

        $caracteres = Caracteres::where('estatus','ACTIVO')->get();
        $acciones = Acciones::where('estatus','ACTIVO')->get();
        $turnados  = Areas::where('idAreaSuperior','DGAJ')->where('estatus','ACTIVO')->get();
        $turnadoDireccion = array ('idArea'=>'DGAJ','nombre' => 'DIRECCIÓN GENERAL DE ASUNTOS JURIDICOS');


        return $this->render('/volantes/update.twig',[
            'sesiones'=> $_SESSION,
            'volantes'=> $volantes,
            'caracteres' => $caracteres,
            'acciones' => $acciones,
            'turnados' => $turnados,
            'direccionGral' => $turnadoDireccion,
            'error' => $err,
            'close' => $close
        ]);

    }

    public function create($post,$app) {
        $fecha=strftime( "%Y-%d-%m", time() );
        $data = $this->duplicateFolio($post);
        if(empty($data)){
            $volantes = new Volantes([
                'idTipoDocto' =>$post['idTipoDocto'],
                'subFolio' => $post['subFolio'],
                'extemporaneo' => $post['extemporaneo'],
                'folio' => $post['folio'],
                'numDocumento' => $post['numDocumento'],
                'anexos' => $post['anexos'],
                'fDocumento' => $post['fDocumento'],
                'fRecepcion' => $post['fRecepcion'],
                'hRecepcion' => $post['hRecepcion'],
                'hRecepcion' => $post['hRecepcion'],
                'idRemitente' => $post['idRemitente'],
                'destinatario' => $post['destinatario'],
                'asunto' => $post['asunto'],
                'idCaracter' => $post['idCaracter'],
                'idTurnado' => $post['idTurnado'],
                'idAccion' => $post['idAccion'],
                'usrAlta' => $_SESSION['idUsuario'],
                'fAlta' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s')
            ]);
            if($volantes->save())
            {
                $max = Volantes::all()->max('idVolante');

                $volantesDocumentos = new VolantesDocumentos([
                    'idVolante' => $max,
                    'promocion' => $post['promocion'],
                    'cveAuditoria' => $post['cveAuditoria'],
                    'idSubTipoDocumento' => $post['idSubTipoDocumento'],
                    'notaConfronta' => $post['notaConfronta'],
                    'usrAlta' => $_SESSION['idUsuario'],
                    'fAlta' => $fecha
                ]);
                $volantesDocumentos->save();
                $notifica = new NotificaController();
                $notifica->notificacionVolantes($post,$app,'Volantes');

            }
        }else{
            echo $this->getCreate('EL numero de FOLIO Y/O SUBFOLIO Ya fue asignado');
        }


    }

    public function update($post,$app) {
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
                'fModificacion' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s'),
                'estatus' => $post['estatus']
            ]);
           $app->redirect('/SIA/juridico/Volantes');


    }

    public function duplicate($post) {
        $duplicate = Caracteres::where('siglas',$post['siglas'])
            ->where('nombre' ,$post['nombre'])
            ->where('estatus',$post['estatus'])->first();
        return $duplicate;
    }

    public function duplicateFolio($post){
        $folio = $post['folio'];
        $subFolio = $post['subFolio'];
        $duplicate = Volantes::where('folio','=',"$folio")
            ->where('subFolio','=',"$subFolio")
            ->first();
        return $duplicate;
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