<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/organization/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-organization'
        ],
        function($request){
            return new Response(200, Application\Organization::getNewOrganization($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/organization/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-organization'
        ],
        function($request){
            return new Response(200, Application\Organization::setNewOrganization($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/organization/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-organization'
        ],
        function($request,$id){
            return new Response(200, Application\Organization::getEditOrganization($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/organization/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-organization'
        ],
        function($request,$id){
            return new Response(200, Application\Organization::setEditOrganization($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/organization/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-organization'
        ],
        function($request,$id){
            return new Response(200, Application\Organization::getDeleteOrganization($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/organization/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-organization'
        ],
        function($request,$id){
            return new Response(200, Application\Organization::setDeleteOrganization($request,$id));
        }
    ]);
}