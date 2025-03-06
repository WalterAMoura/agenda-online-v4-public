<?php

use App\Controller\Api\V1;
use App\Http\Response;

if (isset($obRouter)) {
    //Rota raiz da API
    $obRouter->post('/api/v1/whatsapp/send-message',[
        'middlewares' => [
            'api',
//            'required-apikey',
//            'jwe-auth'
        ],
        function($request){
            return new Response(200, V1\WhatsAppMessageSoundTeam::sendMessage($request),'application/json');
        }
    ]);

    $obRouter->post('/api/v1/whatsapp/reminder',[
        'middlewares' => [
            'api',
        ],
        function($request){
            return new Response(200, V1\WhatsAppMessageSoundTeam::reminder($request),'application/json');
        }
    ]);

    //webhook
    $obRouter->get('/api/v1/whatsapp/webhook',[
        'middlewares' => [
            'webhook-whatsapp'
        ],
        function($request){
            return new Response(200,V1\WhatsAppMessageSoundTeam::webhook($request));
        }
    ]);

    //webhook
    $obRouter->post('/api/v1/whatsapp/webhook',[
        'middlewares' => [
            'api'
        ],
        function($request){
            return new Response(200,V1\WhatsAppMessageSoundTeam::webhook($request),'application/json');
        }
    ]);
}