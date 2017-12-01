<?php
namespace App\Controllers\Catalogs;

use App\Controllers\BaseController;
use App\Models\Caracteres;
use Carbon\Carbon;

class CaracteresController extends BaseController {
   public function getIndex() {

        $this->permisoModulos('CAT-CARACTERES');
        $caracteres = Caracteres::all();
        echo $this->render('caracteres/tabla.twig',[
            'caracteres' => $caracteres,
            'sesiones'=> $_SESSION,
            'notifica' => $this->getNotificaciones(),
            'modulo' => 'Caracteres'
        ]);

   }

   public function getCreate() {
        $this->permisoModulos('CAT-CARACTERES');
        echo $this->render('/caracteres/form.twig',[
          'sesiones'=> $_SESSION,
          'modulo' => 'Caracteres',
          'notifica' => $this->getNotificaciones(),
          ]);
   }

   public function getUpdate($id,$err) {
        $this->permisoModulos('CAT-CARACTERES');
        $caracter = Caracteres::where('idCaracter',$id)->first();
        echo $this->render('/caracteres/update.twig',[
            'sesiones'=> $_SESSION, 
            'caracter'=> $caracter,
            'err' =>$err, 
            'modulo' => 'Caracteres',
            'notifica' => $this->getNotificaciones()
            ]);
   }

   public function create($post,$app) {
       $datos = $this->duplicate($post);
       if(empty($datos)){
            $caracter = new Caracteres([
            'siglas' =>$post['siglas'],
            'nombre' => $post['nombre'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s')
            ]);
            $caracter->save();
            $app->redirect('/SIA/juridico/Caracteres');
       }else{
           return $this->render('/caracteres/form.twig',[
               'sesiones'=> $_SESSION,
               'err' => 'NO puede haber registros Duplicados']);
       }

   }

   public function update($post,$app) {
      $duplicate = $this->duplicate($post);
      if(empty($duplicate)){

          Caracteres::where('idCaracter',$post['idCaracter'])->update([
              'siglas' =>$post['siglas'],
              'nombre' => $post['nombre'],
              'usrModificacion' => $_SESSION['idUsuario'],
              'fModificacion' => Carbon::now('America/Mexico_City')->format('Y-d-m H:i:s'),
              'estatus' => $post['estatus']
          ]);
          $app->redirect('./../Caracteres');
          //echo $this->getIndex();
      }else{

         $this->getUpdate($post['idCaracter'],'Registro Duplicado');
      }

}

public function duplicate($post) {
       if(empty($post['estatus'])){
           $duplicate = Caracteres::where('siglas',$post['siglas'])
               ->where('nombre' ,$post['nombre'])
               ->first();
           return $duplicate;
       }else{

        $duplicate = Caracteres::where('siglas',$post['siglas'])
            ->where('nombre' ,$post['nombre'])
            ->where('estatus',$post['estatus'])->first();
       return $duplicate;
       }
   }

}