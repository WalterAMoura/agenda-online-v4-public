<?php

use App\Http\Response;
use App\Controller\Email;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/email/active-account',[
        'middlewares' => [
        ],
        function($request){
            return new Response(200, Email\ActiveAccount::getActiveAccount($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/email/request-token',[
        'middlewares' => [
        ],
        function($request){
            return new Response(200, Email\ActiveAccount::getNewToken($request));
        }
    ]);
}