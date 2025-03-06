<?php

use App\Http\Response;
use App\Controller\Api;

if (isset($obRouter)) {
    //Rota de envio de sms
    $obRouter->get('/api/v1/events', [
        'middlewares' => [
            'api',
        ],
        function ($request) {
            return new Response(200, Api\V1\Events::getEvents($request),'application/json');
        }
    ]);
}