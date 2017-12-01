<?php

use  App\Controllers\Documentos\DocumentosController;
use App\Controllers\BaseController;

$app->get('/juridico/Documentos/update',function(){
     $get = new DocumentosController();
    echo $get->getCreate(false);
});

$app->post('/juridico/Documentos/update',function() use ($app){
    $get = new DocumentosController();
    echo $get->update($app->request->post(),$_FILES,$app);
});

$app->get('/juridico/Documentos',function() use ($app){
    $get = new DocumentosController();
    echo $get->getIndexDocumentos($app);
});
$app->get('/juridico/Documentos/update/:id',function($id) use ($app){
     $get = new DocumentosController();
    echo $get->getCreate(false,$id);
});

