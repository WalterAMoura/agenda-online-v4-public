<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota de listagem de eventos resumido
    $obRouter->get('/application/calendar-events-church', [
        'middlewares' => [
            'required-login'
        ],
        function ($request) {
            return new Response(200, Application\CalendarEventsChurch::getEvents($request),'application/json');
        }
    ]);


    //Rota de listagem de eventos completo
    $obRouter->get('/application/events-church/calendar-events-church', [
        'middlewares' => [
            'required-login'
        ],
        function ($request) {
            return new Response(200, Application\CalendarEventsChurch::getEventsComplete($request),'application/json');
        }
    ]);
}