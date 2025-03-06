<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-app novo level
    $obRouter->get('/application/advanced-settings/access-modules/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-access-modules'
        ],
        function($request){
            return new Response(200, Application\AccessModule::getNewAccessModules($request));
        }
    ]);

    //Rota cadastrar novo level
    $obRouter->post('/application/advanced-settings/access-modules/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-access-modules'
        ],
        function($request){
            return new Response(200, Application\AccessModule::setNewAccessModules($request));
        }
    ]);

    //Rota config-app edit level
    $obRouter->get('/application/advanced-settings/access-modules/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-access-modules'
        ],
        function($request, $id){
            return new Response(200, Application\AccessModule::getEditAccessModule($request,$id));
        }
    ]);

    //Rota config-app edit level
    $obRouter->post('/application/advanced-settings/access-modules/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-access-modules'
        ],
        function($request, $id){
            return new Response(200, Application\AccessModule::setEditAccessModule($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->get('/application/advanced-settings/access-modules/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-access-modules'
        ],
        function($request, $id){
            return new Response(200, Application\AccessModule::getDeleteAccessModule($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->post('/application/advanced-settings/access-modules/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-access-modules'
        ],
        function($request, $id){
            return new Response(200, Application\AccessModule::setDeleteAccessModule($request,$id));
        }
    ]);
}