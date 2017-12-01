<?php

use  App\Controllers\Documentos\DocumentosUploadController;
use App\Controllers\BaseController;

$app->get('/juridico/DocumentosGral',function()use ($app){
    $get = new DocumentosUploadController();
    echo $get->getIndex($app);
});

$app->get('/juridico/DocumentosGral/update',function(){
     $get = new DocumentosUploadController();
    echo $get->getCreate(false);
});

$app->post('/juridico/DocumentosGral/update',function() use ($app){
    $get = new DocumentosUploadController();
    echo $get->update($app->request->post(),$_FILES,$app);
});


$app->get('/juridico/DocumentosGral/update/:id',function($id) use ($app){
    $get = new DocumentosUploadController();
    echo $get->getCreate(false,$id);
});




