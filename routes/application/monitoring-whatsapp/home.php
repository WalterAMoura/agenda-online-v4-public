<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/monitoring-whatsapp',[
        'middlewares' => [
            'required-login',
            'allow-page-monitoring-whatsapp'
        ],
        function($request){
            return new Response(200,Application\MonitoringWhatsApp::getConfig($request));
        }
    ]);

    $obRouter->get('/application/monitoring-whatsapp/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-monitoring-whatsapp'
        ],
        function($request, $navTab){
            return new Response(200, Application\MonitoringWhatsApp::getConfig($request,$navTab));
        }
    ]);
}