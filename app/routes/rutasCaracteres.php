<?php

use  App\Controllers\Catalogs\CaracteresController;

$app->get('/juridico/Caracteres',function(){
    $get = new CaracteresController();
     echo $get->getIndex();
});

$app->get('/juridico/Caracteres/add',function(){
    $get = new CaracteresController();
    echo $get->getCreate();
});

$app->post('/juridico/Caracteres/add',function() use ($app){
    $get = new CaracteresController();
    echo $get->caracterCreate($app->request->post(),$app);
});


$app->get('/juridico/Caracteres/update/:id',function($id) use ($app){

    $get = new CaracteresController();
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/Caracteres/update',function() use ($app){
    $get = new CaracteresController();
    echo $get->caracterUpdate($app->request->post(),$app);
});
