<?php
namespace App\Controllers\Catalogs;

use App\Controllers\BaseController;
use App\Models\SubTiposDocumentos;
use App\Models\TiposDocumentos;

class SubTiposDocumentosController extends BaseController {
    public function getIndex() {
        $subTipos = SubTiposDocumentos::all();
        return $this->render('/subTiposDocumentos/tabla.twig',['subTipos' => $subTipos,'sesiones'=> $_SESSION]);
    }

    public function getCreate() {
        $tiposDocumentos = TiposDocumentos::where('tipo','JURIDICO')->get();
        return $this->render('/subTiposDocumentos/insert.twig',[
            'sesiones'=> $_SESSION,
            'tiposDocumentos' => $tiposDocumentos
        ]);
    }

    public function getUpdate($id,$err) {
        $subTipos = SubTiposDocumentos::where('idSubTipoDocumento',$id)->first();
        $tiposDocumentos = TiposDocumentos::where('tipo','JURIDICO')->get();

        return $this->render('/subTiposDocumentos/update.twig',[
            'sesiones'=> $_SESSION,
            'documentos' => $tiposDocumentos,
            'subtipos' => $subTipos,
            'err' => $err
        ]);

    }

       public function create($post,$app) {
           $data = $this->duplicate($post);
           if(empty($data)){
           $fecha=strftime( "%Y-%d-%m", time() );
           var_dump($post);
            $subTipo = new SubTiposDocumentos([
                'idTipoDocto' =>$post['idTipoDocto'],
                'nombre' => $post['nombre'],
                'auditoria' => $post['auditoria'],
                'usrAlta' => $_SESSION['idUsuario'],
                'fAlta' => $fecha
            ]);
            $subTipo->save();
            $app->redirect('/SIA/juridico/SubTiposDocumentos');
           }else{
               $tiposDocumentos = TiposDocumentos::where('tipo','JURIDICO')->get();
               return $this->render('/subTiposDocumentos/insert.twig',[
                   'sesiones'=> $_SESSION,
                   'tiposDocumentos' => $tiposDocumentos,
                   'err' => 'NO puede haber registros Duplicados'
               ]);
           }


       }

       public function update($post,$app) {
        $duplicate = $this->duplicate($post);
        if(empty($duplicate)){
            $fecha=strftime( "%Y-%d-%m", time() );
            SubTiposDocumentos::where('idSubTipoDocumento',$post['idSubTipoDocumento'])->update([
                'idTipoDocto' =>$post['idTipoDocto'],
                'nombre' => $post['nombre'],
                'auditoria' => $post['auditoria'],
                'estatus' => $post['estatus'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => $fecha
            ]);
            $app->redirect('/SIA/juridico/SubTiposDocumentos');
        }else{
            echo $this->getUpdate($post['idSubTipoDocumento'],'NO puede haber registros Duplicados');
        }


    }

    public function duplicate($post) {
        if(empty($post['estatus'])){
            $duplicate = SubTiposDocumentos::where('idTipoDocto',$post['idTipoDocto'])
                ->where('nombre' ,$post['nombre'])
                ->where('auditoria',$post['auditoria'])
                ->first();
            return $duplicate;
        }else{
        $duplicate = SubTiposDocumentos::where('idTipoDocto',$post['idTipoDocto'])
            ->where('nombre' ,$post['nombre'])
            ->where('auditoria',$post['auditoria'])
            ->where('estatus',$post['estatus'])->first();
        return $duplicate;
        }
    }

}