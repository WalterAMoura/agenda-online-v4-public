<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/config-event/elder-month/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-month'
        ],
        function($request){
            return new Response(200, Application\ElderMonth::getNewElderMonth($request));
        }
    ]);

    //Rota config-event
    $obRouter->post('/application/config-event/elder-month/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-month'
        ],
        function($request){
            return new Response(200, Application\ElderMonth::setNewElderMonth($request));
        }
    ]);

    //Rota config-event
    $obRouter->get('/application/config-event/elder-month/{monthId}/{yearId}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-month'
        ],
        function($request, $monthId, $yearId){
            return new Response(200, Application\ElderMonth::getEditElderMonth($request, $monthId, $yearId));
        }
    ]);

    //Rota config-event
    $obRouter->post('/application/config-event/elder-month/{monthId}/{yearId}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-month'
        ],
        function($request, $monthId, $yearId){
            return new Response(200, Application\ElderMonth::setEditElderMonth($request, $monthId, $yearId));
        }
    ]);

    //Rota config-event
    $obRouter->get('/application/config-event/elder-month/{monthId}/{yearId}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-month'
        ],
        function($request, $monthId, $yearId){
            return new Response(200, Application\ElderMonth::getDeleteElderMonth($request, $monthId, $yearId));
        }
    ]);

    //Rota config-event
    $obRouter->post('/application/config-event/elder-month/{monthId}/{yearId}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-month'
        ],
        function($request, $monthId, $yearId){
            return new Response(200, Application\ElderMonth::setDeleteElderMonth($request, $monthId, $yearId));
        }
    ]);
}