<?php

use  App\Controllers\Catalogs\CaracteresController;

$get = new CaracteresController();


$app->get('/juridico/Caracteres',function() use($get){
     $get->getIndex();
});

$app->get('/juridico/Caracteres/add',function() use($get){
     $get->getCreate();
});

$app->post('/juridico/Caracteres/add',function() use ($app,$get){
     $get->create($app->request->post(),$app);
});


$app->get('/juridico/Caracteres/update/:id',function($id) use ($app,$get){
     $get->getUpdate($id,false);
});

$app->post('/juridico/Caracteres/update',function() use ($app,$get){
     $get->update($app->request->post(),$app);
});
