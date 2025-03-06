<?php

use App\Controller\Api\V1;
use App\Http\Response;

if (isset($obRouter)) {
    //Rota raiz da API
    $obRouter->get('/api/v1',[
        'middlewares' => [
            'api',
            'required-apikey',
            'jwe-auth'
        ],
        function($request){
            return new Response(200, V1\Api::getDetails($request),'application/json');
        }
    ]);

}