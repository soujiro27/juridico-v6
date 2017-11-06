<?php

use  App\Controllers\Documentos\VolantesController;

$app->get('/juridico/Volantes',function(){
    $get = new VolantesController();
    echo $get->getIndex();
});

$app->get('/juridico/Volantes/add',function(){
    $get = new VolantesController();
    echo $get->getCreate(false);
});

$app->post('/juridico/Volantes/add',function() use ($app){
    $get = new VolantesController();
    echo $get->create($app->request->post(),$app);
});


$app->get('/juridico/Volantes/update/:id',function($id) use ($app){
     $get = new VolantesController();
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/Volantes/update',function() use ($app){
   $get = new VolantesController();
    echo $get->update($app->request->post());
});
