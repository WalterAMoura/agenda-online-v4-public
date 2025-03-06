<?php

use App\Http\Response;
use App\Controller\Api;

if (isset($obRouter)) {
    //Rota de envio de sms
    $obRouter->post('/api/v1/ask-to-change/overdue', [
        'middlewares' => [
            'api',
        ],
        function ($request) {
            return new Response(200, Api\V1\AskToChange::setUpdateOverdue($request),'application/json');
        }
    ]);
}