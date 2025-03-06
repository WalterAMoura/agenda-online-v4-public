<?php

use App\Http\Response;
use App\Controller\Login;

if (isset($obRouter)) {
    // Rota Login GET '/'
    $obRouter->get('/',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\Login::getLogin($request));
        }
    ]);

    // Rota Login GET '/'
    $obRouter->get('',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\Login::getLogin($request));
        }
    ]);

    // Rota Login GET
    $obRouter->get('/login',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){
            return new Response(200, Login\Login::getLogin($request));
        }
    ]);

    // Rota Login POST '/'
    $obRouter->post('/',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){

            return new Response(200, Login\Login::setLogin($request));
        }
    ]);

    // Rota Login POST '/'
    $obRouter->post('',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){

            return new Response(200, Login\Login::setLogin($request));
        }
    ]);

    // Rota Login POST
    $obRouter->post('/login',[
        'middlewares' => [
            'required-logout'
        ],
        function($request){

            return new Response(200, Login\Login::setLogin($request));
        }
    ]);

    //Rota alert Testando
    $obRouter->get('/logout',[
        'middlewares' => [
            'required-alert'
        ],
        function($request){
            return new Response(200, Login\Login::setLogout($request));
        }
    ]);

    //Rota alert Testando
    $obRouter->get('/auto-logout',[
        'middlewares' => [
            'required-alert'
        ],
        function($request){
            return new Response(200, Login\Login::setAutoLogout($request));
        }
    ]);

}