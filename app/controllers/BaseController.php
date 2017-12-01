<?php
namespace App\Controllers;

use Twig_Loader_Filesystem;
use App\Models\Turnos;
use App\Models\Notificaciones;
use App\Models\Roles;
use App\Models\UsuariosRoles;
class BaseController {
    protected $templateEngine;
    public function __construct() {
        $loader = new Twig_Loader_Filesystem('./juridico/views/');
        $this->templateEngine = new \Twig_Environment($loader, [
            'debug' => true,
            'cache' => false
        ]);

        $this->templateEngine->addFilter(new \Twig_SimpleFilter('trim',function($cadena){
            return trim($cadena);
        }));


        $this->templateEngine->addFilter(new \Twig_SimpleFilter('hora',function($cadena){
            return substr($cadena,0,-11);

        }));
    }

    public function render($fileName, $data = []) {
        return $this->templateEngine->render($fileName, $data);
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

    public function getNotificaciones(){
        $id = $_SESSION['idUsuario'];
        $numero = Notificaciones::where('idUsuario','=',"$id")
            ->where('situacion','=','NUEVO')->count();
        if(empty($numero)){
            return 0;
        }else{
            return $numero;
        }
    }

    public function permisoModulos($modulo){
        $user = $_SESSION['idUsuario'];
        $app = \Slim\Slim::getInstance();

        if(empty($user)){
                $app->redirect('/SIA');
        }
        
        $usuario = UsuariosRoles::where('idUsuario','=',"$user")->get();
        $rolUsuario = $usuario[0]['idRol'];
        $rol = Roles::where('idModulo','=',"$modulo")->get();
        $val = false;
        foreach ($rol as $key => $value) {
             if($rol[$key]['idRol'] == $rolUsuario){
                    $val = true;
             }
        }
        if(!$val){
             $app->redirect('/SIA');
             exit();
        }
    }

}