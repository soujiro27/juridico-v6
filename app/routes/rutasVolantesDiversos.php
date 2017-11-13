<?php

use  App\Controllers\Documentos\VolantesDiversosController;

$get = new VolantesDiversosController();

$app->get('/juridico/VolantesDiversos',function() use ($get,$app){
    echo $get->getIndex($app);
});

$app->get('/juridico/VolantesDiversos/add',function() use($get){
    echo $get->getCreate(false);
});

$app->post('/juridico/VolantesDiversos/add',function() use ($app,$get){
    echo $get->volantesCreate($app->request->post(),$app);
});

$app->get('/juridico/VolantesDiversos/update/:id',function($id) use ($app,$get){
    echo $get->getUpdate($id,false);
});

$app->post('/juridico/VolantesDiversos/update',function() use ($app,$get){
    echo $get->volantesUpdate($app->request->post(),$app);
});

