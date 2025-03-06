<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/temp-users/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users'
        ],
        function($request){
            return new Response(200, Application\TempUser::getNewUser($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/temp-users/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users'
        ],
        function($request){
            return new Response(200, Application\TempUser::setNewUser($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/temp-users/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users'
        ],
        function($request,$id){
            return new Response(200, Application\TempUser::getEditUser($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/temp-users/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users'
        ],
        function($request,$id){
            return new Response(200, Application\TempUser::setEditUser($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/temp-users/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users'
        ],
        function($request,$id){
            return new Response(200, Application\TempUser::getDeleteUser($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/temp-users/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users'
        ],
        function($request,$id){
            return new Response(200, Application\TempUser::setDeleteUser($request,$id));
        }
    ]);

    // Rota de aprovação do usuário temporário
    $obRouter->get('/application/advanced-settings/temp-users/{id}/approved',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users',
            'allow-page-approve-temp-user'
        ],
        function($request,$id){
            return new Response(200, Application\TempUser::setApproved($request,$id));
        }
    ]);

    // Rota de reprovação do usuário temporário
    $obRouter->get('/application/advanced-settings/temp-users/{id}/reproved',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-temp-users',
            'allow-page-reprove-temp-user'
        ],
        function($request,$id){
            return new Response(200, Application\TempUser::setReproved($request,$id));
        }
    ]);
}