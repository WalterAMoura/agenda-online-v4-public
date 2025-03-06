<?php

use App\Controller\Api\V1;
use App\Http\Response;

if (isset($obRouter)) {
    //Rota raiz da API
    $obRouter->post('/api/v1/whatsapp-reception/send-message',[
        'middlewares' => [
            'api',
//            'required-apikey',
//            'jwe-auth'
        ],
        function($request){
            return new Response(200, V1\WhatsAppMessageReceptionTeam::sendMessage($request),'application/json');
        }
    ]);

    $obRouter->post('/api/v1/whatsapp-reception/reminder',[
        'middlewares' => [
            'api',
        ],
        function($request){
            return new Response(200, V1\WhatsAppMessageReceptionTeam::reminder($request),'application/json');
        }
    ]);

    //webhook
    $obRouter->get('/api/v1/whatsapp-reception/webhook',[
        'middlewares' => [
            'webhook-whatsapp'
        ],
        function($request){
            return new Response(200,V1\WhatsAppMessageReceptionTeam::webhook($request));
        }
    ]);

    //webhook
    $obRouter->post('/api/v1/whatsapp-reception/webhook',[
        'middlewares' => [
            'api'
        ],
        function($request){
            return new Response(200,V1\WhatsAppMessageReceptionTeam::webhook($request),'application/json');
        }
    ]);
}