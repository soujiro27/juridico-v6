<?php

use  App\Controllers\Documentos\PlantillaController;

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
/*

$app->get('/juridico/Acciones/update',function() use ($app){
    $id='2009';
    $get = new AccionesController();
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/Acciones/update',function() use ($app){
    $get = new AccionesController();
    echo $get->accionesUpdate($app->request->post());
});
*/