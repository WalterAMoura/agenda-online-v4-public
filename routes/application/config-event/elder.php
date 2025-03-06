<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/config-event/elder/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder'
        ],
        function($request){
            return new Response(200, Application\Elder::getNewElder($request));
        }
    ]);

    //Rota config-event
    $obRouter->post('/application/config-event/elder/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder'
        ],
        function($request){
            return new Response(200, Application\Elder::setNewElder($request));
        }
    ]);

    //Rota config-event
    $obRouter->get('/application/config-event/elder/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder'
        ],
        function($request, $id){
            return new Response(200, Application\Elder::getEditElder($request, $id));
        }
    ]);

    //Rota config-event
    $obRouter->post('/application/config-event/elder/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder'
        ],
        function($request, $id){
            return new Response(200, Application\Elder::setEditElder($request, $id));
        }
    ]);

    //Rota config-event
    $obRouter->get('/application/config-event/elder/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder'
        ],
        function($request, $id){
            return new Response(200, Application\Elder::getDeleteElder($request, $id));
        }
    ]);

    //Rota config-event
    $obRouter->post('/application/config-event/elder/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder'
        ],
        function($request, $id){
            return new Response(200, Application\Elder::setDeleteElder($request, $id));
        }
    ]);
}