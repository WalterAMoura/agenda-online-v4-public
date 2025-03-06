<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-app novo level
    $obRouter->get('/application/advanced-settings/level/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-level'
        ],
        function($request){
            return new Response(200, Application\Level::getNewLevel($request));
        }
    ]);

    //Rota cadastrar novo level
    $obRouter->post('/application/advanced-settings/level/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-level'
        ],
        function($request){
            return new Response(200, Application\Level::setNewLevel($request));
        }
    ]);

    //Rota config-app edit level
    $obRouter->get('/application/advanced-settings/level/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-level'
        ],
        function($request, $id){
            return new Response(200, Application\Level::getEditLevel($request,$id));
        }
    ]);

    //Rota config-app edit level
    $obRouter->post('/application/advanced-settings/level/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-level'
        ],
        function($request, $id){
            return new Response(200, Application\Level::setEditLevel($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->get('/application/advanced-settings/level/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-level'
        ],
        function($request, $id){
            return new Response(200, Application\Level::getDeleteLevel($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->post('/application/advanced-settings/level/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-level'
        ],
        function($request, $id){
            return new Response(200, Application\Level::setDeleteLevel($request,$id));
        }
    ]);
}