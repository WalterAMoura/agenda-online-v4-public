<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/worship/worship-team-lineup/new',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-worship'
        ],
        function($request){
            return new Response(200, Application\WorshipTeamLineup::getNewWorshipTeamLineup($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/worship/worship-team-lineup/new',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-worship'
        ],
        function($request){
            return new Response(200, Application\WorshipTeamLineup::setWorshipTeamLineup($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/worship-team-lineup/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-worship'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeamLineup::getEditWorshipTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/worship/worship-team-lineup/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-worship'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeamLineup::setEditWorshipTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/worship-team-lineup/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-worship'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeamLineup::getDeleteWorshipTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/worship/worship-team-lineup/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup-worship'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeamLineup::setDeleteWorshipTeamLineup($request, $id));
        }
    ]);
}