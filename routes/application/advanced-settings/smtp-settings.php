<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/smtp-settings/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-settings-smtp'
        ],
        function($request){
            return new Response(200, Application\SmtpSettings::getNewSmtpSettings($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/smtp-settings/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-settings-smtp'
        ],
        function($request){
            return new Response(200, Application\SmtpSettings::setNewSmtpSettings($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/smtp-settings/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-settings-smtp'
        ],
        function($request,$id){
            return new Response(200, Application\SmtpSettings::getEditSmtpSettings($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/smtp-settings/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-settings-smtp'
        ],
        function($request,$id){
            return new Response(200, Application\SmtpSettings::setEditSmtpSettings($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/smtp-settings/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-settings-smtp'
        ],
        function($request,$id){
            return new Response(200, Application\SmtpSettings::getDeleteSmtpSettings($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/smtp-settings/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-settings-smtp'
        ],
        function($request,$id){
            return new Response(200, Application\SmtpSettings::setDeleteSmtpSettings($request,$id));
        }
    ]);
}