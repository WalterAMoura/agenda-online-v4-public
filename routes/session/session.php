<?php

use App\Http\Response;
use App\Controller\Session;
use App\Session\Users\Login as SessionUsersLogin;

if (isset($obRouter)) {
    //Rotas session
    $obRouter->post('/session',[
//        'middlewares' => [
//            'session-is-open',
//        ],
        function($request){
            return new Response(200, Session\Session::setOnlineGuest($request),'application/json');
        }
    ]);

    //Rotas session check
    $obRouter->post('/session/check',[
//        'middlewares' => [
//            'session-is-open',
//        ],
        function($request){
            return new Response(200, Session\Session::setUpdateTime($request),'application/json');
        }
    ]);

    //Rotas session clean
    $obRouter->post('/session/clean',[
//        'middlewares' => [
//            'session-is-open',
//        ],
        function($request){
            return new Response(200, Session\Session::setZeroTime($request),'application/json');
        }
    ]);
}