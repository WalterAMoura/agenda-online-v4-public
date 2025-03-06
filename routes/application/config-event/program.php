<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-event novo programa ou evento especial
    $obRouter->get('/application/config-event/programs/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-programs'
        ],
        function($request){
            return new Response(200, Application\Program::getNewProgram($request));
        }
    ]);

    //Rota cadastrar novo programa ou evento especial
    $obRouter->post('/application/config-event/programs/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-programs'
        ],
        function($request){
            return new Response(200, Application\Program::setNewProgram($request));
        }
    ]);

    //Rota config-event edit programa ou evento especial
    $obRouter->get('/application/config-event/programs/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-programs'
        ],
        function($request, $id){
            return new Response(200, Application\Program::getEditProgram($request,$id));
        }
    ]);

    //Rota config-event edit programa ou evento especial
    $obRouter->post('/application/config-event/programs/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-programs'
        ],
        function($request, $id){
            return new Response(200, Application\Program::setEditProgram($request,$id));
        }
    ]);

    //Rota config-event delete programa ou evento especial
    $obRouter->get('/application/config-event/programs/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-programs'
        ],
        function($request, $id){
            return new Response(200, Application\Program::getDeleteProgram($request,$id));
        }
    ]);

    //Rota config-event delete programa ou evento especial
    $obRouter->post('/application/config-event/programs/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-programs'
        ],
        function($request, $id){
            return new Response(200, Application\Program::setDeleteProgram($request,$id));
        }
    ]);
}