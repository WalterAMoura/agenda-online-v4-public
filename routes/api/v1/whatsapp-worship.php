<?php

use App\Controller\Api\V1;
use App\Http\Response;

if (isset($obRouter)) {
    //Rota raiz da API
    $obRouter->post('/api/v1/whatsapp-worship/send-message',[
        'middlewares' => [
            'api',
//            'required-apikey',
//            'jwe-auth'
        ],
        function($request){
            return new Response(200, V1\WhatsAppMessageWorshipTeam::sendMessage($request),'application/json');
        }
    ]);

    $obRouter->post('/api/v1/whatsapp-worship/reminder',[
        'middlewares' => [
            'api',
        ],
        function($request){
            return new Response(200, V1\WhatsAppMessageWorshipTeam::reminder($request),'application/json');
        }
    ]);

    //webhook
    $obRouter->get('/api/v1/whatsapp-worship/webhook',[
        'middlewares' => [
            'webhook-whatsapp'
        ],
        function($request){
            return new Response(200,V1\WhatsAppMessageWorshipTeam::webhook($request));
        }
    ]);

    //webhook
    $obRouter->post('/api/v1/whatsapp-worship/webhook',[
        'middlewares' => [
            'api'
        ],
        function($request){
            return new Response(200,V1\WhatsAppMessageWorshipTeam::webhook($request),'application/json');
        }
    ]);
}