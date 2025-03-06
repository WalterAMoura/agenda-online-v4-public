<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/email-alert-configuration/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-email-alarmes'
        ],
        function($request){
            return new Response(200, Application\EmailAlarmes::getNewEmailAlarmes($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/email-alert-configuration/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-email-alarmes'
        ],
        function($request){
            return new Response(200, Application\EmailAlarmes::setNewEmailAlarmes($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/email-alert-configuration/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-email-alarmes'
        ],
        function($request,$id){
            return new Response(200, Application\EmailAlarmes::getEditEmailAlertas($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/email-alert-configuration/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-email-alarmes'
        ],
        function($request,$id){
            return new Response(200, Application\EmailAlarmes::setEditEmailAlarmes($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/email-alert-configuration/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-email-alarmes'
        ],
        function($request,$id){
            return new Response(200, Application\EmailAlarmes::getDeleteEmailAlert($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/email-alert-configuration/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-email-alarmes'
        ],
        function($request,$id){
            return new Response(200, Application\EmailAlarmes::setDeleteEmailAlert($request,$id));
        }
    ]);
}