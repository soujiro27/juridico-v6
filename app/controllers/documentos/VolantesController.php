<?php
namespace App\Controllers\Documentos;


use App\Controllers\BaseController;
use App\Models\Acciones;
use App\Models\Areas;
use App\Models\PuestosJuridico;
use App\Models\SubTiposDocumentos;
use App\Models\TiposDocumentos;
use App\Models\Volantes;
use \App\Models\Caracteres;
use App\Models\VolantesDocumentos;
use App\Models\Notificaciones;

class VolantesController extends BaseController {
    public function getIndex() {

        $volantes = VolantesDocumentos::select('v.idVolante','v.folio','v.subfolio','v.numDocumento','v.idRemitente'
            ,'v.idTurnado','v.fRecepcion','v.extemporaneo','a.clave','sub.nombre','t.estadoProceso','v.estatus')
            ->join('sia_Volantes as v','v.idVolante','=','sia_volantesDocumentos.idVolante')
            ->join('sia_turnosJuridico as t','t.idVolante','=','v.idVolante'  )
            ->join('sia_auditorias as a','a.idAuditoria','=','sia_volantesDocumentos.cveAuditoria')
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','sia_volantesDocumentos.idSubTipoDocumento')
            ->where('sub.auditoria','SI')->get();
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
            'error' => $err
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
                'fAlta' => $fecha
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
                $remitente = $post['idRemitente'];
                $area = PuestosJuridico::select('u.idUsuario')
                    ->join('sia_usuarios as u','u.idEmpleado','=','sia_PuestosJuridico.rpe')
                    ->where('sia_PuestosJuridico.idArea','=',"$remitente")
                    ->where('sia_PuestosJuridico.titular','=','SI')->get();
                /*$idUsuario = $area['idUsuario'];
                $notifica = new Notificaciones([
                    'idNotificacion' => '11',
                    'idUsuario' => $idUsuario,
                    'mensaje' => 'Tienes un nuevo Volante Asignado',
                    'idPrioridad' => 'ALTA',
                    'idImpacto' => 'MEDIO',
                    'fLectura' => $fecha,
                    'usrAlta' => $_SESSION['idUsuario'],
                    'fAlta' => $fecha,
                    'estatus' => 'ACTIVO',
                    'situacion' => 'NUEVO',
                    'identificador' => '1',
                    'idCuenta' => 'CTA-2016',
                    'idAuditoria' => '1',
                    'idModulo' => 'Volantes',
                    'referencia' => 'idVolante'

                        $notifica->save()
                ]);*/

                    $app->redirect('/SIA/juridico/Volantes');

            }
        }else{
            echo $this->getCreate('EL numero de FOLIO Y/O SUBFOLIO Ya fue asignado');
        }




    }

    public function update($post) {


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

}