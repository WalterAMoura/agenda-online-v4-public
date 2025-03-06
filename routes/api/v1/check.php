<?php

use App\Controller\Api\V1;
use App\Http\Response;

if (isset($obRouter)) {
    //Rota validar token de usuário
    $obRouter->post('/api/v1/check', [
        'middlewares' => [
            'api',
            'required-apikey'
        ],
        function ($request) {
            return new Response(200, V1\Auth::checkToken($request),'application/json');
        }
    ]);
}