<?php

use  App\Controllers\Documentos\IracController;
use App\Controllers\BaseController;

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
    $get->observacionesCreate($app->request->post(),$app);
});

$app->get('/juridico/observacionesIrac/update/:id',function($id) use ($app){
    $get = new IracController();
    echo $get->getUpdateObservacion($id,false);
});


$app->post('/juridico/observacionesIrac/update',function() use ($app){
    $get = new IracController();
    echo $get->observacionUpdate($app->request->post(),$app);
});

$app->get('/juridico/CedulaIrac/add/:id',function($id){
    $get = new IracController();
    echo $get->getCreateCedula($id);
});

$app->post('/juridico/CedulaIrac/add',function() use ($app){
    $get = new IracController();
    $get->cedulaCreate($app->request->post(),$app);
});


$app->post('/juridico/CedulaIrac/update',function() use ($app){
    $get = new IracController();
    echo $get->cedulaUpdate($app->request->post(),$app);
});


