<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/application',[
        'middlewares' => [
            'required-login',
            'allow-page-home'
        ],
        function($request){
            return new Response(200, Application\Home::getHome($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/home',[
        'middlewares' => [
            'required-login',
            'allow-page-home'
        ],
        function($request){
            return new Response(200, Application\Home::getHome($request));
        }
    ]);
}