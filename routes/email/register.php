<?php

use App\Http\Response;
use App\Controller\Email;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/email/confirmed-register',[
        'middlewares' => [
        ],
        function($request){
            return new Response(200, Email\Register::getActiveAccount($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/email/register-request-token',[
        'middlewares' => [
        ],
        function($request){
            return new Response(200, Email\Register::getNewToken($request));
        }
    ]);
}