<?php

use  App\Controllers\Documentos\IfaController;
use App\Controllers\BaseController;

$app->get('/juridico/Ifa',function(){
    $get = new IfaController();
    echo $get->getIndex();
});

$app->get('/juridico/observacionesIfa/:id',function($id){
    $get = new IfaController();
    echo $get->getObservaciones($id);
});


$app->get('/juridico/observacionesIfa/add/:id',function($id){
    $get = new IfaController();
    echo $get->getCreateObservacion($id);
});

$app->post('/juridico/observacionesIfa/add',function() use ($app){
    $get = new IfaController();
    $get->observacionesCreate($app->request->post(),$app);
});

$app->get('/juridico/observacionesIfa/update/:id',function($id) use ($app){

    $get = new IfaController();
    echo $get->getUpdateObservacion($id,false);
});


$app->post('/juridico/observacionesIfa/update',function() use ($app){
    $get = new IfaController();
    echo $get->observacionUpdate($app->request->post(),$app);
});

$app->get('/juridico/CedulaIfa/add/:id',function($id){
    $get = new IfaController();
    echo $get->getCreateCedula($id);
});

$app->post('/juridico/CedulaIfa/add',function() use ($app){
    $get = new IfaController();
    $get->cedulaCreate($app->request->post(),$app);
});


$app->post('/juridico/CedulaIfa/update',function() use ($app){
    $get = new IfaController();
    echo $get->cedulaUpdate($app->request->post(),$app);
});


