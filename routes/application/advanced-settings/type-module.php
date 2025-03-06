<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-app novo level
    $obRouter->get('/application/advanced-settings/type-module/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-type-module'
        ],
        function($request){
            return new Response(200, Application\TypeModule::getNewTypeModule($request));
        }
    ]);

    //Rota cadastrar novo level
    $obRouter->post('/application/advanced-settings/type-module/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-type-module'
        ],
        function($request){
            return new Response(200, Application\TypeModule::setNewTypeModule($request));
        }
    ]);

    //Rota config-app edit level
    $obRouter->get('/application/advanced-settings/type-module/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-type-module'
        ],
        function($request, $id){
            return new Response(200, Application\TypeModule::getEditTypeModule($request,$id));
        }
    ]);

    //Rota config-app edit level
    $obRouter->post('/application/advanced-settings/type-module/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-type-module'
        ],
        function($request, $id){
            return new Response(200, Application\TypeModule::setEditTypeModule($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->get('/application/advanced-settings/type-module/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-type-module'
        ],
        function($request, $id){
            return new Response(200, Application\TypeModule::getDeleteTypeModule($request,$id));
        }
    ]);

    //Rota config-app delete level
    $obRouter->post('/application/advanced-settings/type-module/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-type-module'
        ],
        function($request, $id){
            return new Response(200, Application\TypeModule::setDeleteTypeModule($request,$id));
        }
    ]);
}