<?php

use  App\Controllers\Catalogs\AccionesController;

$app->get('/juridico/Acciones',function(){
    $get = new AccionesController();
    echo $get->getIndex();
});

$app->get('/juridico/Acciones/add',function(){
    $get = new AccionesController();
    echo $get->getCreate();
});

$app->post('/juridico/Acciones/add',function() use ($app){
    $get = new AccionesController();
    $get->create($app->request->post(),$app);
});


$app->get('/juridico/Acciones/update/:id',function($id) use ($app){
    $get = new AccionesController();
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/Acciones/update',function() use ($app){
    $get = new AccionesController();
    echo $get->update($app->request->post(),$app);
});
