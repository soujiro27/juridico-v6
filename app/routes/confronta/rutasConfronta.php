<?php

use  App\Controllers\Documentos\Confronta\ConfrontaController;

$app->get('/juridico/confrontasJuridico',function(){
    $get = new ConfrontaController();
    echo $get->getIndex();
});



$app->get('/juridico/confrontasJuridico/add/:id',function($id){
    $id = '3212';
    $get = new ConfrontaController();
    echo $get->getCreate($id);
});



$app->post('/juridico/confrontasJuridico/add',function() use ($app){
    $get = new ConfrontaController();
    $get->create($app->request->post());
});


$app->post('/juridico/confrontasJuridico/update',function() use ($app){
    $get = new ConfrontaController();
    echo $get->update($app->request->post());
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