<?php

use  App\Controllers\Documentos\IracController;

$app->get('/juridico/Irac',function(){
    $get = new IracController();
    echo $get->getIndex();
});

$app->get('/juridico/observacionesIrac/:id',function($id){
    $get = new IracController();
    echo $get->getObervaciones($id);
});


$app->get('/juridico/observacionesIrac/add/:id',function($id){
    $get = new IracController();
    echo $get->getCreateObservacion($id);
});

$app->post('/juridico/observacionesIrac/add',function() use ($app){
    $get = new IracController();
    $get->observacionesCreate($app->request->post());
});

$app->get('/juridico/observacionesIrac/update/:id',function($id) use ($app){
    $get = new IracController();
    echo $get->getUpdateObservacion($id,false);
});


$app->post('/juridico/observacionesIrac/update',function() use ($app){
    $get = new IracController();
    echo $get->observacionUpdate($app->request->post());
});

$app->get('/juridico/CedulaIrac/add/:id',function($id){
    $get = new IracController();
    echo $get->getCreateCedula($id);
});

$app->post('/juridico/CedulaIrac/add',function() use ($app){
    $get = new IracController();
    $get->cedulaCreate($app->request->post());
});


$app->post('/juridico/CedulaIrac/update',function() use ($app){
    $get = new IracController();
    echo $get->cedulaUpdate($app->request->post());
});

/*
$app->get('/juridico/Acciones/add',function(){
    $get = new AccionesController();
    echo $get->getCreate();
});

$app->post('/juridico/Acciones/add',function() use ($app){
    $get = new AccionesController();
    $get->accionesCreate($app->request->post());
});


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