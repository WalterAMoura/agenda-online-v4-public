<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota de listagem de eventos completo
    $obRouter->get('/application/event/list-all', [
        'middlewares' => [
            'required-login'
        ],
        function ($request) {
            return new Response(200, Application\ListAll::getListAll($request));
        }
    ]);
}