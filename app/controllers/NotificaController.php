<?php
namespace App\Controllers;

use App\Models\Notificaciones;
use App\Models\PuestosJuridico;
use App\Models\SubTiposDocumentos;
use Carbon\Carbon;

class NotificaController {
   public function notificacionVolantes($post,$app,$ruta){
       $fecha=strftime( "%Y-%d-%m", time() );
       $turnado = $post['idTurnado'];

       if($turnado == 'DGAJ' ){
           $area = PuestosJuridico::select('u.idUsuario')
               ->join('sia_usuarios as u','u.idEmpleado','=','sia_PuestosJuridico.rpe')
               ->where('sia_PuestosJuridico.idArea','=',"$turnado")
               ->where('sia_PuestosJuridico.recepcion','=','SI')->get();
           $idUsuario = $area[0]['idUsuario'];
       }else{


           $area = PuestosJuridico::select('u.idUsuario')
               ->join('sia_usuarios as u','u.idEmpleado','=','sia_PuestosJuridico.rpe')
               ->where('sia_PuestosJuridico.idArea','=',"$turnado")
               ->where('sia_PuestosJuridico.titular','=','SI')->get();
       }
       $idUsuario = $area[0]['idUsuario'];

       $id = $post['idSubTipoDocumento'];
       $subTipo = SubTiposDocumentos::where('idSubTipoDocumento','=',"$id")->get();

       $mensaje = 'Tienes un: '. $subTipo[0]['nombre'].' Asignado con el Folio: '.$post['folio'];

       $notifica = new Notificaciones([
           'idNotificacion' => '1',
           'idUsuario' => $idUsuario,
           'mensaje' => $mensaje,
           'idPrioridad' => 'ALTA',
           'idImpacto' => 'MEDIO',
           'fLectura' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s'),
           'usrAlta' => $_SESSION['idUsuario'],
           'fAlta' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s'),
           'estatus' => 'ACTIVO',
           'situacion' => 'NUEVO',
           'identificador' => '1',
           'idCuenta' => 'CTA-2016',
           'idAuditoria' => '1',
           'idModulo' => 'Volantes',
           'referencia' => 'idVolante'

       ]);
       $notifica->save();

       $app->redirect('/SIA/juridico/'.$ruta);
   }
}