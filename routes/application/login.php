<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota alert
    $obRouter->get('/application/logout', [
        'middlewares' => [
            'required-alert'
        ],
        function ($request) {
            return new Response(200, Application\Login::setLogout($request));
        }
    ]);
}