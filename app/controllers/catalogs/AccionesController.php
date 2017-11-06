<?php
namespace App\Controllers\Catalogs;

use App\Controllers\BaseController;
use App\Models\Acciones;

class AccionesController extends BaseController {
    public function getIndex() {
        $acciones = Acciones::all();
        return $this->render('Acciones/tabla.twig',['acciones' => $acciones,'sesiones'=> $_SESSION]);
    }

    public function getCreate() {
            return $this->render('/Acciones/insert.twig',['sesiones'=> $_SESSION]);

    }


    public function create($post,$app){
        $data = $this->duplicate($post);
        if(empty($data)){

        $fecha=strftime( "%Y-%d-%m", time() );
        $acciones = new Acciones([
            'nombre' => $post['nombre'],
            'usrAlta' => $_SESSION['idUsuario'],
            'fAlta' => $fecha
        ]);
        $acciones->save();
        $app->redirect('/SIA/juridico/Acciones');
        }else{
            echo $this->render('/Acciones/insert.twig',[
                'sesiones'=> $_SESSION,
                'err' => 'No puede haber datos Duplicados']);
        }

    }


    public function getUpdate($id,$err) {

        $accion = Acciones::where('idAccion',$id)->first();
        return $this->render('/Acciones/update.twig',[
            'sesiones'=> $_SESSION,
            'accion'=> $accion,
            'err' => $err
        ]);
    }

    public function update($post,$app) {
        $duplicate = $this->duplicate($post);
        if(empty($duplicate)){
            $fecha=strftime( "%Y-%d-%m", time() );
            Acciones::where('idAccion',$post['idAccion'])->update([
                'nombre' => $post['nombre'],
                'usrModificacion' => $_SESSION['idUsuario'],
                'fModificacion' => $fecha,
                'estatus' => $post['estatus']
            ]);
            $app->redirect('/SIA/juridico/Acciones');
        }else{

            echo $this->getUpdate($post['idAccion'],'NO puede haber datos duplicados');
        }

    }

    public function duplicate($post) {
        if(empty($post['estatus'])){
            $duplicate = Acciones::where('nombre' ,$post['nombre'])
                ->first();
            return $duplicate;
        }else{
            $duplicate = Acciones::where('nombre' ,$post['nombre'])
                ->where('estatus',$post['estatus'])
                ->first();
            return $duplicate;
        }

    }

}