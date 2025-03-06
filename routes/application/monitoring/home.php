<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/monitoring',[
        'middlewares' => [
            'required-login',
            'allow-page-monitoring'
        ],
        function($request){
            return new Response(200,Application\Monitoring::getConfig($request));
        }
    ]);

    $obRouter->get('/application/monitoring/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-monitoring'
        ],
        function($request, $navTab){
            return new Response(200, Application\Monitoring::getConfig($request,$navTab));
        }
    ]);
}