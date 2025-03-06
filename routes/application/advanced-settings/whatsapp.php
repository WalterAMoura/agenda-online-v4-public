<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    // Rota de cadastro de um novo usuário
    $obRouter->get('/application/advanced-settings/whatsapp/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-access-token-whatsapp'
        ],
        function($request){
            return new Response(200, Application\AccessTokenWhatsApp::getNewAccessTokenWhatsApp($request));
        }
    ]);

    // POST de cadastro de um novo usuário
    $obRouter->post('/application/advanced-settings/whatsapp/new',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-access-token-whatsapp'
        ],
        function($request){
            return new Response(200, Application\AccessTokenWhatsApp::setNewAccessTokenWhatsApp($request));
        }
    ]);

    // Rota de edição de um novo usuário
    $obRouter->get('/application/advanced-settings/whatsapp/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-access-token-whatsapp'
        ],
        function($request,$id){
            return new Response(200, Application\AccessTokenWhatsApp::getEditAccessTokenWhatsApp($request,$id));
        }
    ]);

    // Rota de edição de um novo usuário [POST]
    $obRouter->post('/application/advanced-settings/whatsapp/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-access-token-whatsapp'
        ],
        function($request,$id){
            return new Response(200, Application\AccessTokenWhatsApp::setEditTokenWhatsApp($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->get('/application/advanced-settings/whatsapp/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-access-token-whatsapp'
        ],
        function($request,$id){
            return new Response(200, Application\AccessTokenWhatsApp::getDeleteTokenWhatsApp($request,$id));
        }
    ]);

    // Rota de exclusão de um novo usuário
    $obRouter->post('/application/advanced-settings/whatsapp/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings',
            'allow-page-manager-access-token-whatsapp'
        ],
        function($request,$id){
            return new Response(200, Application\AccessTokenWhatsApp::setDeleteTokenWhatsApp($request,$id));
        }
    ]);
}