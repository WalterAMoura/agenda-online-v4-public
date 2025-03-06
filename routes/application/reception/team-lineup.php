<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/reception/reception-team-lineup/new',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-reception'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeamLineup::getNewReceptionTeamLineup($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/reception-team-lineup/new',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-reception'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeamLineup::setReceptionTeamLineup($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/reception-team-lineup/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-reception'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeamLineup::getEditReceptionTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/reception-team-lineup/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-reception'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeamLineup::setEditReceptionTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/reception-team-lineup/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-reception'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeamLineup::getDeleteReceptionTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/reception-team-lineup/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-reception'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeamLineup::setDeleteReceptionTeamLineup($request, $id));
        }
    ]);
}