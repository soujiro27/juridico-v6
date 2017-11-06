<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\Acciones;
use App\Models\SubTiposDocumentos;
use App\Models\Auditorias;
use App\Models\Remitentes;
use App\Models\Volantes;
use App\Models\PuestosJuridico;

class ApiController extends BaseController {
    public function getSubDocumentos($valor) {
        $sub = SubTiposDocumentos::where('idTipoDocto','=',"$valor")->get();
        $sub->toJson();
        echo json_encode($sub);
    }

    public function getSubDocumentosWithAuditoria($valor) {
        $sub = SubTiposDocumentos::where('idTipoDocto','=',"$valor")
            ->where('auditoria','=','SI')
            ->get();
        $sub->toJson();
        echo json_encode($sub);
    }

    public function getSubDocumentosWithOutAuditoria($valor) {
        $sub = SubTiposDocumentos::where('idTipoDocto','=',"$valor")
            ->where('auditoria','=','NO')
            ->get();
        $sub->toJson();
        echo json_encode($sub);
    }

    /*Cambiar esta Funcion porqueria */
    public function auditorias($datos){
        include("src/conexion.php");
        try{
            $db = new \PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
        }catch (PDOException $e) {
            print "ERROR: " . $e->getMessage();
            die();
        }
        $cuenta=$_SESSION['idCuentaActual'];
        $clave = $datos['clave'];

        $query = "select a.idAuditoria,a.tipoAuditoria as tipo,a.rubros,a.idArea,
u.nombre as sujeto
 from sia_auditorias a
inner join sia_auditoriasunidades au on au.idAuditoria=a.idAuditoria
inner join sia_unidades u on u.idUnidad = au.idUnidad
 where a.clave = '$clave' and au.idSector=u.idSector and au.idSubsector=u.idSubsector and u.idUnidad = au.idUnidad
 and au.idCuenta = u.idCuenta";
        $sql = $db->prepare($query);
        $sql->execute();
        $res = $sql->fetchAll(\PDO::FETCH_ASSOC);
        echo json_encode($res);


    }

    public function turnadoAuditoria($datos){

        include("src/conexion.php");
        try{
            $db = new \PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
        }catch (PDOException $e) {
            print "ERROR: " . $e->getMessage();
            die();
        }
        $cveAuditoria = $datos['cveAuditoria'];
        $query = "select sub.nombre, v.idTurnado as turnado from sia_VolantesDocumentos vd
        inner join sia_Volantes v on vd.idVolante=v.idVolante
        inner join sia_catSubTiposDocumentos sub on vd.idSubTipoDocumento = sub.idSubTipoDocumento
        where cveAuditoria='$cveAuditoria'";
        $sql = $db->prepare($query);
        $sql->execute();
        $res = $sql->fetchAll(\PDO::FETCH_ASSOC);
        echo json_encode($res);
    }


    public function remitentes () {
        $remitentes  = Remitentes::where('estatus','=','ACTIVO')->get();
        echo json_encode($remitentes);
    }

    public function volantesByFolio($id){
        $folio = $id['folio'];
        $volantes = Volantes::where('folio','=',"$folio")->get();
        echo json_encode($volantes);
    }

    public function firmas(){

       $idArea = $_SESSION['idArea'];

        $puestos = PuestosJuridico::where('idArea','=',"$idArea")
            ->where('titular','=','NO')->get();
        echo json_encode($puestos);
    }
}