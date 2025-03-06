<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota application
    $obRouter->post('/application/events-church/events-church-drop',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-events-church'
        ],
        function($request){
            return new Response(200, Application\EventsChruchDrop::setEventDrop($request),'application/json');
        }
    ]);

}