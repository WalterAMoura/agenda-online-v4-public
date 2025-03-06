<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota alert
    $obRouter->post('/application/update-passwd', [
        'middlewares' => [
            'required-login',
            'allow-update-passwd'
        ],
        function ($request) {
            return new Response(200, Application\User::setPasswd($request));
        }
    ]);
}