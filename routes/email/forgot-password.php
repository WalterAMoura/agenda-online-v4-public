<?php

use App\Http\Response;
use App\Controller\Email;
use App\Controller\Login;

if (isset($obRouter)) {
    // Rota Forgot Password GET '/'
    $obRouter->get('/email/set-new-password',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\ForgotPassword::getNewPassword($request));
        }
    ]);

    // Rota Forgot Password POST '/'
    $obRouter->post('/email/set-new-password',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\ForgotPassword::setNewPassword($request));
        }
    ]);
}