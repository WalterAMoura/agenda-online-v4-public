<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/users/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request){
            return new Response(200, Application\User::getNewUser($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/users/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request){
            return new Response(200, Application\User::setNewUser($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/users/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request,$id){
            return new Response(200, Application\User::getEditUser($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/users/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request,$id){
            return new Response(200, Application\User::setEditUser($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/users/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request,$id){
            return new Response(200, Application\User::getDeleteUser($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/users/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request,$id){
            return new Response(200, Application\User::setDeleteUser($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/users/{id}/reset-pass',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request,$id){
            return new Response(200, Application\User::setResetPasswd($request,$id));
        }
    ]);

    // Rota de reenvia ativação da conta
    $obRouter->get('/application/advanced-settings/users/{id}/resend-account-activate',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-user'
        ],
        function($request,$id){
            return new Response(200, Application\User::resendAccountActivate($request,$id));
        }
    ]);
}