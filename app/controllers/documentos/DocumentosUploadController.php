<?php
namespace App\Controllers\Documentos;

use App\Controllers\BaseController;
use App\Models\Volantes;
use App\Models\PuestosJuridico;

class DocumentosUploadController extends BaseController {
    public function getIndex() {
        $documentos = Volantes::select('sia_volantes.idVolante','sia_volantes.folio','sia_volantes.numDocumento',
            'sub.nombre','sia_volantes.idRemitente','sia_volantes.anexoDoc','t.estadoProceso')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_volantes.idVolante')
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->join('sia_turnosJuridico as t','t.idVolante','=','sia_volantes.idVolante')
            ->get();

        return $this->render('/documentos/tabla.twig',['documentos' => $documentos,'sesiones'=> $_SESSION]);
    }

    public function getCreate() {
        $duplicate = false;
        return $this->render('/documentos/insert.twig',['sesiones'=> $_SESSION]);
    }


    public function update($post,$file) {
        $nombre=$file['anexoDoc']['name'];
        $extension=explode('.',$nombre);
        if(move_uploaded_file($file['anexoDoc']['tmp_name'],"juridico/public/files/".$post['idVolante'].'.'.$extension[1]))
        {
            $fecha=strftime( "%Y-%d-%m", time() );
            Volantes::where('idVolante',$post['idVolante'])->update([
                'anexoDoc' => $post['idVolante'].'.'.$extension[1],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => $fecha
            ]);
            echo $this->getIndex();
        }


    }


    public function duplicate($post) {
        $duplicate = DocumentosUploadController::where('idVolante' ,$post['idVolante'])
            ->first();
        return $duplicate;
    }

    public function getIndexDocumentos() {

        $id = $_SESSION['idEmpleado'];
        $areas = PuestosJuridico::all()->where('rpe','=',"$id");
        foreach ($areas as $area) {$areaUsuario=$area['idArea'];}


        $documentos = Volantes::select('sia_volantes.idVolante','sia_volantes.folio','sia_volantes.numDocumento',
            'sub.nombre','sia_volantes.idRemitente','sia_volantes.anexoDoc','t.estadoProceso')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_volantes.idVolante')
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->join('sia_turnosJuridico as t','t.idVolante','=','sia_volantes.idVolante')
            ->where('sia_Volantes.idTurnado','=',"$areaUsuario")
            ->get();

        return $this->render('/documentos/tabla.twig',['documentos' => $documentos,'sesiones'=> $_SESSION]);
    }

}