<?php

use  App\Controllers\Catalogs\DoctosTextosController;
$get = new DoctosTextosController();
$app->get('/juridico/DoctosTextos',function() use($get){
     echo $get->getIndex();
});

$app->get('/juridico/DoctosTextos/add',function() use($get){
    echo $get->getCreate();
});

$app->post('/juridico/DoctosTextos/add',function() use ($app,$get){
    echo $get->create($app->request->post(),$app);
});


$app->get('/juridico/DoctosTextos/update/:id',function($id) use ($app,$get){
    echo $get->getUpdate($id);
});

$app->post('/juridico/DoctosTextos/update',function() use ($app,$get){
    echo $get->update($app->request->post(),$app);
});
