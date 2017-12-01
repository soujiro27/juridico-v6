<?php
namespace App\Controllers\Documentos;

use App\Controllers\BaseController;
use App\Models\Volantes;
use App\Models\PuestosJuridico;

class DocumentosController extends BaseController {


        public function getCreate($err,$id) {
        //$this->permisoModulos('DOCUMENTOSGRAL');
        $nombre = Volantes::where('idVolante','=',"$id")->get();
        return $this->render('/documentos-sub/insert.twig',[
            'sesiones'=> $_SESSION,
            'err' => $err,
            'nombre' => $nombre[0]['anexoDoc'],
            'idVolante' => $id,
              'modulo' => 'Archivos',
            'notifica' => $this->getNotificaciones()
        ]);
    }

    public function update($post,$file,$app) {
        $nombre=$file['anexoDoc']['name'];
        $extension=explode('.',$nombre);
        if(count($extension)>1){
        if(move_uploaded_file($file['anexoDoc']['tmp_name'],"juridico/public/files/".$post['idVolante'].'.'.$extension[1]))
        {
            $fecha=strftime( "%Y-%d-%m", time() );
            Volantes::where('idVolante',$post['idVolante'])->update([
                'anexoDoc' => $post['idVolante'].'.'.$extension[1],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => $fecha
            ]);
            $app->redirect('/SIA/juridico/Documentos');
        }
        }else{
            echo $this->getCreate('El Archivo Contiene un Formato Incorrecto');
        }

    }


    public function duplicate($post) {
        $duplicate = DocumentosUploadController::where('idVolante' ,$post['idVolante'])
            ->first();
        return $duplicate;
    }

    public function getIndexDocumentos($app) {

          $this->permisoModulos('DOCUMENTOSJUR');

 if(empty($app->request->get()))
            {
                $campo = 'folio';
                $tipo = 'desc';
            }else{
                $get = $app->request->get();
                $campo = $get['campo'];
                $tipo = $get['tipo'];
            }


        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}


        $documentos = Volantes::select('sia_volantes.idVolante','sia_volantes.folio','sia_volantes.subFolio','sia_volantes.numDocumento',
            'sub.nombre','sia_volantes.idRemitente','sia_volantes.anexoDoc','t.estadoProceso')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_volantes.idVolante')
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->join('sia_turnosJuridico as t','t.idVolante','=','sia_volantes.idVolante')
            ->where('sia_Volantes.idTurnado','=',"$areaUsuario")
            ->orderBy("$campo","$tipo")
            ->get();

        return $this->render('/documentos-sub/tabla.twig',['documentos' => $documentos,'sesiones'=> $_SESSION]);
    }

}