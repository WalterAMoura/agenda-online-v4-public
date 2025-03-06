<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota de listagem de eventos resumido
    $obRouter->get('/application/calendar', [
        'middlewares' => [
            'required-login'
        ],
        function ($request) {
            return new Response(200, Application\Calendar::getEvents($request),'application/json');
        }
    ]);


    //Rota de listagem de eventos completo
    $obRouter->get('/application/event/calendar', [
        'middlewares' => [
            'required-login'
        ],
        function ($request) {
            return new Response(200, Application\Calendar::getEventsComplete($request),'application/json');
        }
    ]);
}