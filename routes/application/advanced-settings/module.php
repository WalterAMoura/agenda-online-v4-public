<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-app novo level
    $obRouter->get('/application/advanced-settings/modules/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-modules'
        ],
        function($request){
            return new Response(200, Application\Module::getNewModule($request));
        }
    ]);

    //Rota cadastrar novo level
    $obRouter->post('/application/advanced-settings/modules/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-modules'
        ],
        function($request){
            return new Response(200, Application\Module::setNewModule($request));
        }
    ]);

    //Rota config-app edit level
    $obRouter->get('/application/advanced-settings/modules/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-modules'
        ],
        function($request, $id){
            return new Response(200, Application\Module::getEditModule($request,$id));
        }
    ]);

    //Rota config-app edit level
    $obRouter->post('/application/advanced-settings/modules/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-modules'
        ],
        function($request, $id){
            return new Response(200, Application\Module::setEditModule($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->get('/application/advanced-settings/modules/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-modules'
        ],
        function($request, $id){
            return new Response(200, Application\Module::getDeleteModule($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->post('/application/advanced-settings/modules/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-modules'
        ],
        function($request, $id){
            return new Response(200, Application\Module::setDeleteModule($request,$id));
        }
    ]);
}