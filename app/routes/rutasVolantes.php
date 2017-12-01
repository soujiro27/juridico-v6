<?php

use  App\Controllers\Documentos\VolantesController;
use App\Controllers\BaseController;
$get = new VolantesController();

$app->get('/juridico/Volantes',function() use($get,$app){
    echo $get->getIndex($app);
});

$app->get('/juridico/Volantes/add',function() use($get){
    echo $get->getCreate(false);
});

$app->post('/juridico/Volantes/add',function() use ($app,$get){
    echo $get->create($app->request->post(),$app);
});


$app->get('/juridico/Volantes/update/:id', function($id) use ($app,$get){
    echo $get->getUpdate($id,false);

});

$app->post('/juridico/Volantes/update',function() use ($app,$get){
    echo $get->update($app->request->post(),$app);
});

