<?php

use  App\Controllers\Catalogs\AccionesController;
$get = new AccionesController();
$app->get('/juridico/Acciones',function() use($get){
    echo $get->getIndex();
});

$app->get('/juridico/Acciones/add',function() use($get){
    echo $get->getCreate();
});

$app->post('/juridico/Acciones/add',function() use ($app,$get){
    $get->create($app->request->post(),$app);
});


$app->get('/juridico/Acciones/update/:id',function($id) use ($app,$get){
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/Acciones/update',function() use ($app,$get){
    echo $get->update($app->request->post(),$app);
});
