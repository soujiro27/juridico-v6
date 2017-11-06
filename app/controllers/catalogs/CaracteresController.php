<?php
namespace App\Controllers\Catalogs;

use App\Controllers\BaseController;
use App\Models\Caracteres;

class CaracteresController extends BaseController {
   public function getIndex() {
        $caracteres = Caracteres::all();
        //return $this->render('layout.twig');
        return $this->render('caracteres/tabla.twig',['caracteres' => $caracteres,'sesiones'=> $_SESSION]);
   }

   public function getCreate() {
        $duplicate = false;
        return $this->render('/caracteres/form.twig',['sesiones'=> $_SESSION]);
   }

   public function getUpdate($id) {

        $caracter = Caracteres::where('idCaracter',$id)->first();
        return $this->render('/caracteres/update.twig',[
            'sesiones'=> $_SESSION, 
            'caracter'=> $caracter
            ]);
   }

   public function caracterCreate($post,$app) {
       $datos = $this->duplicate($post);
       if(empty($datos)){
            $fecha=strftime( "%Y-%d-%m", time() );
            $caracter = new Caracteres([
            'siglas' =>$post['siglas'],
            'nombre' => $post['nombre'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => $fecha
            ]);
            $caracter->save();
            $app->redirect('/SIA/juridico/Caracteres');
       }else{
           return $this->render('/caracteres/form.twig',[
               'sesiones'=> $_SESSION,
               'err' => 'NO puede haber registros Duplicados']);
       }

   }

   public function caracterUpdate($post,$app) {
      $duplicate = $this->duplicate($post);
      if(empty($duplicate)){
          $fecha=strftime( "%Y-%d-%m", time() );
          Caracteres::where('idCaracter',$post['idCaracter'])->update([
              'siglas' =>$post['siglas'],
              'nombre' => $post['nombre'],
              'usrModificacion' => $_SESSION['idUsuario'],
              'fModificacion' => $fecha,
              'estatus' => $post['estatus']
          ]);
          $app->redirect('./../Caracteres');
          //echo $this->getIndex();
      }else{

          $caracter = Caracteres::where('idCaracter',$post['idCaracter'])->first();
          return $this->render('/caracteres/update.twig',[
              'sesiones'=> $_SESSION,
              'caracter'=> $caracter,
              'err' => 'NO puede haber registros Duplicados'
          ]);
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