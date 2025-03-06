<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota de listagem de usuários
    $obRouter->get('/application/table-events-church', [
        'middlewares' => [
            'required-login'
        ],
        function ($request) {
            return new Response(200, Application\TableEventsChurch::getEvents($request), 'application/json');
        }
    ]);
}