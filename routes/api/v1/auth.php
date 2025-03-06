<?php

use App\Controller\Api\V1;
use App\Http\Response;
use App\Utils\Debug;

if (isset($obRouter)) {
    //Rota gerar token de usuário
    $obRouter->post('/api/v1/auth', [
        'middlewares' => [
            'api',
            'required-apikey'
        ],
        function ($request) {
            return new Response(200, V1\Auth::getToken($request),'application/json');
        }
    ]);
}