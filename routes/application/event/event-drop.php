<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota application
    $obRouter->post('/application/event/event-drop',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-event'
        ],
        function($request){
            return new Response(200, Application\EventDrop::setEventDrop($request),'application/json');
        }
    ]);

}