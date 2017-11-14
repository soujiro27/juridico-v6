<?php
namespace App\Controllers\Documentos;

use App\Controllers\BaseController;
use App\Models\ConfrontasJuridico;
use App\Models\DocumentosSiglas;
use App\Models\ObservacionesDoctosJuridico;
use App\Models\Volantes;
use App\Models\PuestosJuridico;
use App\Models\VolantesDocumentos;
use Carbon\Carbon;

class confrontaController extends BaseController {
    public function getIndex()
    {
        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}


        $iracs = Volantes::select('sia_Volantes.idVolante','sia_Volantes.folio',
            'sia_Volantes.numDocumento','sia_Volantes.idRemitente','sia_Volantes.fRecepcion','sia_Volantes.asunto'
            ,'c.nombre as caracter','a.nombre as accion','audi.clave','tj.estadoProceso','sia_Volantes.extemporaneo')
            ->join('sia_catCaracteres as c','c.idCaracter','=','sia_Volantes.idCaracter')
            ->join('sia_CatAcciones as a','a.idAccion','=','sia_Volantes.idAccion')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_Volantes.idVolante')
            ->join('sia_auditorias as audi','audi.idAuditoria','=','vd.cveAuditoria')
            ->join( 'sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->join('sia_turnosJuridico as tj','tj.idVolante','sia_Volantes.idVolante')
            ->where('sub.nombre','=','CONFRONTA')
            ->where('sia_volantes.idTurnado','=',"$areaUsuario")
            ->get();


        return $this->render('confronta/tablas.twig',['iracs' => $iracs,'sesiones'=> $_SESSION]);
    }

    public function getCreate($idVolante) {
        $notas = VolantesDocumentos::all()->where('idVolante','=',"$idVolante");
        foreach ($notas as $valor) {$nota = $valor['notaConfronta'];}

        $confrontas  = ConfrontasJuridico::where('idVolante','=',"$idVolante")->first();
        if($this->verificaVolante($idVolante)){$err = false;}else{$err = 'EL Documento ha sido CERRADO';}

        if(empty($confrontas)){
            return $this->render('/confronta/insert.twig',[
                'sesiones'=> $_SESSION,
                'nota' => $nota,
                'idVolante' => $idVolante]);
        }else{
            return $this->render('/confronta/update.twig',[
                'sesiones'=> $_SESSION,
                'nota' => $nota,
                'confrontas' => $confrontas,
                'close' => $this->verificaVolante($idVolante),
                'err' => $err
                ]);
        }

    }


    public function create($post,$app) {

        if(empty($post['notaInformativa'])) {
            $confrontas = new ConfrontasJuridico([
                'idVolante'=> $post['idVolante'],
                'nombreResponsable' => $post['nombreResponsable'],
                'cargoResponsable' => $post['cargoResponsable'],
                'siglas' => $post['siglas'],
                'hConfronta' => $post['hConfronta'],
                'fOficio' => $post['fOficio'],
                'fConfronta' => $post['fConfronta'],
                'numFolio' => $post['numFolio'],
                'usrAlta' => $_SESSION['idUsuario'],
                'fAlta' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s')
            ]);
            $confrontas->save();
            $app->redirect('/SIA/juridico/confrontasJuridico');
        }else{
            $confrontas = new ConfrontasJuridico([
                'notaInformativa' => $post['notaInformativa'],
                'idVolante'=> $post['idVolante'],
                'nombreResponsable' => $post['nombreResponsable'],
                'cargoResponsable' => $post['cargoResponsable'],
                'siglas' => $post['siglas'],
                'hConfronta' => $post['hConfronta'],
                'fOficio' => $post['fOficio'],
                'fConfronta' => $post['fConfronta'],
                'numFolio' => $post['numFolio'],
                'usrAlta' => $_SESSION['idUsuario'],
                'fAlta' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s')
            ]);
            $confrontas->save();
            $app->redirect('/SIA/juridico/confrontasJuridico');
        }

    }





    public function update($post,$app) {
            $id = $post['idConfrontaJuridico'];
            $fecha=strftime( "%Y-%d-%m", time() );
        if(empty($post['notaInformativa'])) {
            ConfrontasJuridico::where('idConfrontaJuridico','=',"$id")
            ->update([
                'idVolante'=> $post['idVolante'],
                'nombreResponsable' => $post['nombreResponsable'],
                'cargoResponsable' => $post['cargoResponsable'],
                'siglas' => $post['siglas'],
                'hConfronta' => $post['hConfronta'],
                'fOficio' => $post['fOficio'],
                'fConfronta' => $post['fConfronta'],
                'numFolio' => $post['numFolio'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' =>  Carbon::now('America/Mexico_City')->format('Y-m-d')
            ]);
            $app->redirect('/SIA/juridico/confrontasJuridico');
        }else{
            ConfrontasJuridico::where('idConfrontaJuridico','=',"$id")
            ->update([
                'notaInformativa' => $post['notaInformativa'],
                'idVolante'=> $post['idVolante'],
                'nombreResponsable' => $post['nombreResponsable'],
                'cargoResponsable' => $post['cargoResponsable'],
                'siglas' => $post['siglas'],
                'hConfronta' => $post['hConfronta'],
                'fOficio' => $post['fOficio'],
                'fConfronta' => $post['fConfronta'],
                'numFolio' => $post['numFolio'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => Carbon::now('America/Mexico_City')->format('Y-m-d')
            ]);
            $app->redirect('/SIA/juridico/confrontasJuridico');
        }

    }

    public function duplicate($post) {
        $duplicate = Acciones::where('nombre' ,$post['nombre'])
            ->where('estatus',$post['estatus'])
            ->first();
        return $duplicate;
    }


}