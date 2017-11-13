<?php

include '/../vendor/autoload.php';
include_once '/../app/routes/rutasCaracteres.php';
include_once '/../app/routes/rutasDoctosTextos.php';
include_once '/../app/routes/rutasSubTiposDocumentos.php';
include_once '/../app/routes/rutasAcciones.php';
include_once  '/../app/routes/rutasVolantes.php';
include_once  '/../app/routes/rutasVolantesDiversos.php';
include_once  '/../app/routes/rutasDocumentosGral.php';
include_once  '/../app/routes/rutasDocumentos.php';
include_once  '/../app/routes/rutasIrac.php';
include_once  '/../app/routes/rutasIfa.php';
include_once  '/../app/routes/rutasConfronta.php';
include_once  '/../app/routes/rutasPlantillas.php';
include_once  '/../app/routes/datosApi/rutasDatosApi.php';

use Illuminate\Database\Capsule\Manager as Capsule;

include_once '/../../src/conexion.php';

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'sqlsrv',
    'host'      => $hostname,
    'database'  => $database,
    'username'  => $username,
    'password'  => $password,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();




