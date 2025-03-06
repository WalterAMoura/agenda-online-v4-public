<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota
    $obRouter->get('/application/monitoring-whatsapp/{navTab}/{id}/view',[
        'middlewares' => [
            'required-login',
            'allow-page-monitoring-whatsapp'
        ],
        function($request, $navTab,$id){
            return new Response(200, Application\WhatsAppView::getSearchView($request,$navTab,$id));
        }
    ]);
}