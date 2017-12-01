<?php

use  App\Controllers\Documentos\ConfrontaController;
use App\Controllers\BaseController;

$app->get('/juridico/confrontasJuridico',function(){
    $get = new ConfrontaController();
    echo $get->getIndex();
});



$app->get('/juridico/confrontasJuridico/add/:id',function($id){
    $get = new ConfrontaController();
    echo $get->getCreate($id);
});



$app->post('/juridico/confrontasJuridico/add',function() use ($app){
    $get = new ConfrontaController();
    $get->create($app->request->post(),$app);
});


$app->post('/juridico/confrontasJuridico/update',function() use ($app){
    $get = new ConfrontaController();
    echo $get->update($app->request->post(),$app);
});
