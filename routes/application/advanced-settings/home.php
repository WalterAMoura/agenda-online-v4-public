<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    $obRouter->get('/application/advanced-settings',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings'
        ],
        function($request){
            return new Response(200, Application\AdvancedSettings::getConfig($request));
        }
    ]);

    $obRouter->get('/application/advanced-settings/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-advanced-settings'
        ],
        function($request, $navTab){
            return new Response(200, Application\AdvancedSettings::getConfig($request,$navTab));
        }
    ]);
}