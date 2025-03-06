<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-event novo level
    $obRouter->get('/application/config-event/status-event/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-status-event'
        ],
        function($request){
            return new Response(200, Application\StatusEvent::getNewStatusEvent($request));
        }
    ]);

    //Rota cadastrar novo status event
    $obRouter->post('/application/config-event/status-event/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-status-event'
        ],
        function($request){
            return new Response(200, Application\StatusEvent::setNewEvent($request));
        }
    ]);

    //Rota config-event edit status event
    $obRouter->get('/application/config-event/status-event/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-status-event'
        ],
        function($request, $id){
            return new Response(200, Application\StatusEvent::getEditStatusEvent($request,$id));
        }
    ]);

    //Rota config-event edit status event
    $obRouter->post('/application/config-event/status-event/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-status-event'
        ],
        function($request, $id){
            return new Response(200, Application\StatusEvent::setEditStatusEvent($request,$id));
        }
    ]);

    //Rota config-event delete level
    $obRouter->get('/application/config-event/status-event/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-status-event'
        ],
        function($request, $id){
            return new Response(200, Application\StatusEvent::getDeleteStatusEvent($request,$id));
        }
    ]);

    //Rota config-event delete level
    $obRouter->post('/application/config-event/status-event/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-status-event'
        ],
        function($request, $id){
            return new Response(200, Application\StatusEvent::setDeleteStatusEvent($request,$id));
        }
    ]);
}