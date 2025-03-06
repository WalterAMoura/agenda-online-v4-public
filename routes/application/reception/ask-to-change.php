<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/reception/ask-to-change/requested',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request){
            return new Response(200, Application\ReceptionAskToChange::getListMyAskToChange($request,'requested'));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/ask-to-change/received',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request){
            return new Response(200, Application\ReceptionAskToChange::getListMyAskToChange($request,'received'));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/ask-to-change/{id}/accepted',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionAskToChange::setAskToChangeStatus($request, $id, 2));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/ask-to-change/{id}/rejected',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionAskToChange::setAskToChangeStatus($request, $id, 3));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/ask-to-change/{id}/canceled',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionAskToChange::setAskToChangeStatus($request, $id, 4));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/ask-to-change/{id}',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionAskToChange::getAskToChange($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/ask-to-change/{id}',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionAskToChange::setAskToChange($request, $id));
        }
    ]);
}