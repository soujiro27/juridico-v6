<?php

use  App\Controllers\Documentos\DocumentosUploadController;

$app->get('/juridico/DocumentosGral',function(){
    $get = new DocumentosUploadController();
    echo $get->getIndex();
});

$app->get('/juridico/DocumentosGral/update',function(){
     $get = new DocumentosUploadController();
    echo $get->getCreate();
});

$app->post('/juridico/DocumentosGral/update',function() use ($app){
    $get = new DocumentosUploadController();
    echo $get->update($app->request->post(),$_FILES);
});

$app->get('/juridico/Documentos',function(){
    $get = new DocumentosUploadController();
    echo $get->getIndexDocumentos();
});


