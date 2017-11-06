<?php

use  App\Controllers\Catalogs\DoctosTextosController;

$app->get('/juridico/DoctosTextos',function(){
    $get = new DoctosTextosController();
     echo $get->getIndex();
});

$app->get('/juridico/DoctosTextos/add',function(){
    $get = new DoctosTextosController();
    echo $get->getCreate();
});

$app->post('/juridico/DoctosTextos/add',function() use ($app){
    $get = new DoctosTextosController();
    echo $get->create($app->request->post(),$app);
});


$app->get('/juridico/DoctosTextos/update/:id',function($id) use ($app){

    $get = new DoctosTextosController();
    echo $get->getUpdate($id);
});

$app->post('/juridico/DoctosTextos/update',function() use ($app){
    $get = new DoctosTextosController();
    echo $get->update($app->request->post(),$app);
});
