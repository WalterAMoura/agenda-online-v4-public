<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/application/scheduler-worship-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getEvents($request),'application/json');
        }
    ]);
    //Rota agenda
    $obRouter->get('/application/worship/scheduler-worship-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getEvents($request),'application/json');
        }
    ]);

    //Rota agenda
//    $obRouter->get('/application/scheduler-teams-lineup/items-teams-lineup',[
//        'middlewares' => [
//            'required-login',
//            'allow-page-manager-sound-team'
//        ],
//        function($request){
//            return new Response(200, Application\WorshipTeam::getSchedulerSoundTeamLineupItems($request),'application/json');
//        }
//    ]);
//
//    //Rota agenda
//    $obRouter->get('/application/scheduler-teams-lineup/items-teams-lineup/filter',[
//        'middlewares' => [
//            'required-login',
//            'allow-page-manager-sound-team'
//        ],
//        function($request){
//            return new Response(200, Application\WorshipTeam::getSchedulerSoundTeamLineupFilterItems($request),'application/json');
//        }
//    ]);

    //Rota agenda
    $obRouter->get('/application/worship/manager-team-worship/new',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getNewWorshipTeam($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/worship/manager-team-worship/new',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::setWorshipTeam($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/manager-team-worship/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeam::getEditWorshipTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/worship/manager-team-worship/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeam::setEditWorshipTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/manager-team-worship/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeam::getDeleteWorshipTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/worship/manager-team-worship/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request, $id){
            return new Response(200, Application\WorshipTeam::setDeleteWorshipTeam($request, $id));
        }
    ]);
}