<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/application/dashboards',[
        'middlewares' => [
            'required-login',
            'allow-page-home'
        ],
        function($request){
            return new Response(200, Application\Dashboards::getHome($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/dashboards/home',[
        'middlewares' => [
            'required-login',
            'allow-page-home'
        ],
        function($request){
            return new Response(200, Application\Dashboards::getHome($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/dashboards/{id}/{year}/view/{type}',[
        'middlewares' => [
            'required-login',
            'allow-page-home'
        ],
        function($request, $id, $year, $type){
            return new Response(200, Application\Dashboards::getSearchView($request,$id,$year,$type));
        }
    ]);
}