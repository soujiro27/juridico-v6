<?php

use  App\Controllers\Documentos\PlantillaController;
use App\Controllers\BaseController;

$app->get('/juridico/DocumentosDiversos',function(){
    $get = new PlantillaController();
    echo $get->getIndex();
});

$app->get('/juridico/DocumentosDiversos/add/:id',function($id){
    $get = new PlantillaController();
    echo $get->getCreate($id);
});

$app->post('/juridico/DocumentosDiversos/add',function() use ($app){
    $get = new PlantillaController();
    $get->create($app->request->post(),$app);
});

$app->post('/juridico/DocumentosDiversos/update',function() use ($app){
    $get = new PlantillaController();
    echo $get->update($app->request->post(),$app);
});



