<?php

use App\Http\Response;
use App\Controller\Login;

if (isset($obRouter)) {
    // Rota Forgot Password GET '/'
    $obRouter->get('/register',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\Register::getRegister($request));
        }
    ]);

    // Rota Forgot Password POST '/'
    $obRouter->post('/register',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\Register::setRegister($request));
        }
    ]);
}