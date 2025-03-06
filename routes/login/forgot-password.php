<?php

use App\Http\Response;
use App\Controller\Login;

if (isset($obRouter)) {
    // Rota Forgot Password GET '/'
    $obRouter->get('/forgot-password',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\ForgotPassword::getForgotPassword($request));
        }
    ]);

    // Rota Forgot Password POST '/'
    $obRouter->post('/forgot-password',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\ForgotPassword::setForgotPassword($request));
        }
    ]);
}