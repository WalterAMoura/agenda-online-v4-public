<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/config-event',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event'
        ],
        function($request){
            return new Response(200, Application\ConfigEvent::getConfig($request));
        }
    ]);

    $obRouter->get('/application/config-event/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event'
        ],
        function($request, $navTab){
            return new Response(200, Application\ConfigEvent::getConfig($request,$navTab));
        }
    ]);
}