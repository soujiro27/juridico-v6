<?php
namespace App\Controllers\Documentos;

use App\Controllers\BaseController;
use App\Models\Volantes;
use App\Models\PuestosJuridico;
use App\Models\Turnos;
class DocumentosUploadController extends BaseController {
    public function getIndex() {
        $documentos = Volantes::select('sia_volantes.idVolante','sia_volantes.folio','sia_volantes.subFolio','sia_volantes.numDocumento',
            'sub.nombre','sia_volantes.idRemitente','sia_volantes.anexoDoc','t.estadoProceso')
            ->join('sia_VolantesDocumentos as vd','vd.idVolante','=','sia_volantes.idVolante')
            ->join('sia_catSubTiposDocumentos as sub','sub.idSubTipoDocumento','=','vd.idSubTipoDocumento')
            ->join('sia_turnosJuridico as t','t.idVolante','=','sia_volantes.idVolante')
            ->get();

        return $this->render('/documentos/tabla.twig',['documentos' => $documentos,'sesiones'=> $_SESSION]);
    }

    public function getCreate($err) {
        return $this->render('/documentos/insert.twig',[
            'sesiones'=> $_SESSION,
            'err' => $err
        ]);
    }


    public function update($post,$file,$app) {
        $id = $post['idVolante'];
        if($this->verificaVolante($id)){
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
                    $app->redirect('/SIA/juridico/DocumentosGral');
                }
            }else{
                echo $this->getCreate('El Archivo Contiene un Formato Incorrecto');
            }
        }else{
            echo $this->getCreate('El Volante ha sido CERRADO no puede agregar documentos');
        }


    }


    public function duplicate($post) {
        $duplicate = DocumentosUploadController::where('idVolante' ,$post['idVolante'])
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