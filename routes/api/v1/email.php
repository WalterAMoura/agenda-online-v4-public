<?php

use App\Http\Response;
use App\Controller\Email;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/api/v1/email/active-account',[
        'middlewares' => [
            'required-apikey',
            'jwe-auth'
        ],
        function($request){
            return new Response(200, Email\ActiveAccount::sendEmailActiveAccountApi($request), 'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->post('/api/v1/email/active-account',[
        'middlewares' => [
            'required-apikey',
            'jwe-auth'
        ],
        function($request){
            return new Response(200, Email\ActiveAccount::sendEmailActiveAccountApi($request), 'application/json');
        }
    ]);
}