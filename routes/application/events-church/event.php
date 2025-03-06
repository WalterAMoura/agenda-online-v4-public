<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota carregar tabela mais calendário para consultas
    $obRouter->get('/application/events-church', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function ($request) {
            return new Response(200, Application\EventsChurch::getEvents($request));
        }
    ]);

    //Busca evento especifico
    $obRouter->get('/application/events-church/{id}/search', [
        'middlewares' => [
            'required-login',
        ],
        function ($request, $id) {
            return new Response(200, Application\EventsChurch::getEventById($request, $id),'application/json');
        }
    ]);

    // Rota carrega calendário para incluir ou editar um evento
    $obRouter->get('/application/events-church/home', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function ($request) {
            return new Response(200, Application\EventsChurch::getNewEvent($request));
        }
    ]);

    // POST de editar evento
    $obRouter->post('/application/events-church/edit', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function ($request) {
            return new Response(200, Application\EventsChurch::setNewEditEvent($request));
        }
    ]);

    // POST de cadastro de um novo evento
    $obRouter->post('/application/events-church/new', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function ($request) {
            return new Response(200, Application\EventsChurch::setNewEvent($request));
        }
    ]);

    // Remover evento
    $obRouter->get('/application/events-church/{id}/delete', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function ($request, $id) {
            return new Response(200, Application\EventsChurch::getDeleteEvent($request, $id));
        }
    ]);

    // Remover evento
    $obRouter->post('/application/events-church/{id}/delete', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function ($request, $id) {
            return new Response(200, Application\EventsChurch::setDeleteEvent($request, $id));
        }
    ]);
}