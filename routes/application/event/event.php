<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota carregar tabela mais calendário para consultas
    $obRouter->get('/application/event', [
        'middlewares' => [
            'required-login',
        ],
        function ($request) {
            return new Response(200, Application\Event::getEvents($request));
        }
    ]);

    //Busca evento especifico
    $obRouter->get('/application/event/{id}/search', [
        'middlewares' => [
            'required-login',
        ],
        function ($request, $id) {
            return new Response(200, Application\Event::getEventById($request, $id),'application/json');
        }
    ]);

    // Rota carrega calendário para incluir ou editar um evento
    $obRouter->get('/application/event/home', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-event'
        ],
        function ($request) {
            return new Response(200, Application\Event::getNewEvent($request));
        }
    ]);

    // POST de editar evento
    $obRouter->post('/application/event/edit', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-event'
        ],
        function ($request) {
            return new Response(200, Application\Event::setNewEditEvent($request));
        }
    ]);

    // POST de cadastro de um novo evento
    $obRouter->post('/application/event/new', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-event'
        ],
        function ($request) {
            return new Response(200, Application\Event::setNewEvent($request));
        }
    ]);

    // Remover evento
    $obRouter->get('/application/event/{id}/delete', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-event'
        ],
        function ($request, $id) {
            return new Response(200, Application\Event::getDeleteEvent($request, $id));
        }
    ]);

    // Remover evento
    $obRouter->post('/application/event/{id}/delete', [
        'middlewares' => [
            'required-login',
            'allow-page-manager-event'
        ],
        function ($request, $id) {
            return new Response(200, Application\Event::setDeleteEvent($request, $id));
        }
    ]);
}