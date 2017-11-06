<?php

use  App\Controllers\Catalogs\SubTiposDocumentosController;

$app->get('/juridico/SubTiposDocumentos',function(){
    $get = new SubTiposDocumentosController();
    echo $get->getIndex();
});

$app->get('/juridico/SubTiposDocumentos/add',function(){
     $get = new SubTiposDocumentosController();
    echo $get->getCreate();
});

$app->post('/juridico/SubTiposDocumentos/add',function() use ($app){
    $get = new SubTiposDocumentosController();
    echo $get->create($app->request->post(),$app);
});


$app->get('/juridico/SubTiposDocumentos/update/:id',function($id) use ($app){
    $get = new SubTiposDocumentosController();
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/SubTiposDocumentos/update',function() use ($app){
    $get = new SubTiposDocumentosController();
    echo $get->update($app->request->post(),$app);
});
