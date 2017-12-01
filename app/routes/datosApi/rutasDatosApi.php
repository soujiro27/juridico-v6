<?php

use  App\Controllers\Api\ApiController;

$app->get('/juridico/datos/Sessions',function() use($app){
    $datos = array(
        "idUsuario"         => $_SESSION['idUsuario'],
        "idCuentaActual"    => $_SESSION['idCuentaActual'],
        "userName"          => $_SESSION['sUsuario'],
        "cuenta"            => $_SESSION['sCuentaActual'],
    );

    echo json_encode($datos);

});

$app->get('/juridico/datos/subDocumentos',function() use($app){
    $valor = $app->request->get();
    $get = new ApiController();
    echo $get->getSubDocumentos($valor['dato']);
});

$app->get('/juridico/datos/subDocumentosAuditoria',function() use($app){
    $valor = $app->request->get();
    $get = new ApiController();
    echo $get->getSubDocumentosWithAuditoria($valor['dato']);
});

$app->get('/juridico/datos/subDocumentosNoAuditoria',function() use($app){
    $valor = $app->request->get();
    $get = new ApiController();
    echo $get->getSubDocumentosWithOutAuditoria($valor['dato']);
});


$app->get('/juridico/datos/auditoria',function() use($app){
    $get = new ApiController();
    $get->auditorias($app->request->get());
});

$app->get('/juridico/datos/turnadoAuditoria',function() use($app){
    $get = new ApiController();
    $get->turnadoAuditoria($app->request->get());
});

$app->get('/juridico/datos/remitentes',function() use($app){
    $get = new ApiController();
    $get->remitentes();
});

$app->get('/juridico/datos/volanteByFolio',function() use($app){
    $get = new ApiController();
    $get->volantesByFolio($app->request->get());
});

$app->get('/juridico/datos/firmas',function() use($app){
    $get = new ApiController();
    $get->firmas();
});

$app->get('/juridico/datos/doctosTextos',function() use($app){
    $get = new ApiController();
    $get->doctosTextos();
});

$app->get('/juridico/datos/remitentesPlantilla',function() use($app){
    $get = new ApiController();
    $get->remitentesPlantilla($app->request->get());
});

$app->post('/juridico/datos/closeVolante',function() use($app){
    $get = new ApiController();
    $get->closeVolante($app->request->post(),$app);
});

$app->get('/juridico/datos/puestos',function() use($app){
    $get = new ApiController();
    $get->puestos();
});