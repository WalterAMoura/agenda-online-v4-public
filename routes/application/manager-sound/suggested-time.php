<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/suggested-time/new',[
        'middlewares' => [
            'required-login',
            'allow-page-suggested-time'
        ],
        function($request){
            return new Response(200, Application\SuggestedTime::getNewSuggestedTime($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/suggested-time/new',[
        'middlewares' => [
            'required-login',
            'allow-page-suggested-time'
        ],
        function($request){
            return new Response(200, Application\SuggestedTime::setSuggestedTime($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/suggested-time/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-suggested-time'
        ],
        function($request, $id){
            return new Response(200, Application\SuggestedTime::getEditSuggestedTime($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/suggested-time/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-suggested-time'
        ],
        function($request, $id){
            return new Response(200, Application\SuggestedTime::setEditSuggestedTime($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/suggested-time/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-suggested-time'
        ],
        function($request, $id){
            return new Response(200, Application\SuggestedTime::getDeleteSuggestedTime($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/suggested-time/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-suggested-time'
        ],
        function($request, $id){
            return new Response(200, Application\SuggestedTime::setDeleteSuggestedTime($request, $id));
        }
    ]);
}