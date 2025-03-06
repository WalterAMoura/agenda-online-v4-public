<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/ask-to-change/requested',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request){
            return new Response(200, Application\AskToChange::getListMyAskToChange($request,'requested'));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/ask-to-change/received',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request){
            return new Response(200, Application\AskToChange::getListMyAskToChange($request,'received'));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/ask-to-change/{id}/accepted',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\AskToChange::setAskToChangeStatus($request, $id, 2));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/ask-to-change/{id}/rejected',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\AskToChange::setAskToChangeStatus($request, $id, 3));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/ask-to-change/{id}/canceled',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\AskToChange::setAskToChangeStatus($request, $id, 4));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/ask-to-change/{id}',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\AskToChange::getAskToChange($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/ask-to-change/{id}',[
        'middlewares' => [
            'required-login',
            'allow-page-ask-to-change'
        ],
        function($request, $id){
            return new Response(200, Application\AskToChange::setAskToChange($request, $id));
        }
    ]);
}