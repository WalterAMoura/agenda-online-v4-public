<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/apikey/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-apikey'
        ],
        function($request){
            return new Response(200, Application\Apikey::getNewApikey($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/apikey/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-apikey'
        ],
        function($request){
            return new Response(200, Application\Apikey::setNewApikey($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/apikey/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-apikey'
        ],
        function($request,$id){
            return new Response(200, Application\Apikey::getEditApikey($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/apikey/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-apikey'
        ],
        function($request,$id){
            return new Response(200, Application\Apikey::setEditApikey($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/apikey/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-apikey'
        ],
        function($request,$id){
            return new Response(200, Application\Apikey::getDeleteApikey($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/apikey/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-apikey'
        ],
        function($request,$id){
            return new Response(200, Application\Apikey::setDeleteApikey($request,$id));
        }
    ]);
}