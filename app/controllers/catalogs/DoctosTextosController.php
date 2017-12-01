<?php
namespace App\Controllers\Catalogs;

use App\Controllers\BaseController;
use App\Models\DoctosTextos;
use App\Models\TiposDocumentos;
use App\Models\SubTiposDocumentos;


class DoctosTextosController extends BaseController {
   public function getIndex() {
      $this->permisoModulos('CAT-DOCTOSTEXTOS');
       $doctosTextos = DoctosTextos::all();
       return $this->render('doctosTextos/tabla.twig',[
        'doctosTextos' => $doctosTextos,
        'sesiones'=> $_SESSION,
          'modulo' => 'Textos',
          'notifica' => $this->getNotificaciones(),
        ]);
   }

   public function getCreate() {
    $this->permisoModulos('CAT-DOCTOSTEXTOS');
       $tiposDocumentos = TiposDocumentos::where('tipo','JURIDICO')->get();
        return $this->render('/doctosTextos/insert.twig',[
        'sesiones'=> $_SESSION,
        'tiposDocumentos' => $tiposDocumentos,
         'modulo' => 'Textos',
          'notifica' => $this->getNotificaciones(),
        ]);
   }

   public function getUpdate($id) {
     $this->permisoModulos('CAT-DOCTOSTEXTOS');
        $doctoTexto = DoctosTextos::where('idDocumentoTexto',$id)->first();
        $tiposDocumentos = TiposDocumentos::where('tipo','JURIDICO')->get();
        $subTipos = SubTiposDocumentos::where('idTipoDocto',$doctoTexto['idTipoDocto'])->get();
        return $this->render('/doctosTextos/update.twig',[
            'sesiones'=> $_SESSION, 
            'doctoTexto' => $doctoTexto,
            'documentos' => $tiposDocumentos,
            'subtipos' => $subTipos,
            'modulo' => 'Textos',
            'notifica' => $this->getNotificaciones(),
            ]);

   }

   public function create($post,$app) {
       $fecha=strftime( "%Y-%d-%m", time() );
        $caracter = new DoctosTextos([
            'idTipoDocto' =>$post['idTipoDocto'],
            'idSubTipoDocumento' => $post['idSubTipoDocumento'],
            'texto' => $post['texto'],
            'tipo' => $post['tipo'],
            'nombre' => $post['nombre'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => $fecha
        ]);
        $caracter->save();
      // $app->redirect('/SIA/juridico/DoctosTextos');

   }

   public function update($post,$app) {
    $fecha=strftime( "%Y-%d-%m", time() );
    DoctosTextos::where('idDocumentoTexto',$post['idDocumentoTexto'])->update([
        'idTipoDocto' =>$post['idTipoDocto'],
        'idSubTipoDocumento' => $post['idSubTipoDocumento'],
        'texto' => $post['texto'],
        'estatus' => $post['estatus'],
        'usrModificacion' => $_SESSION['idUsuario'],
        'fModificacion' => $fecha
     ]);
     $app->redirect('/SIA/juridico/DoctosTextos');

}
}