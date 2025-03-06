<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/worship',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getConfig($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/scheduler-worship-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getSchedulerWorshipTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/scheduler-worship-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getSchedulerWorshipTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/scheduler-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getSchedulerWorshipTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/scheduler-worship-teams-lineup/items-teams-lineup/filter',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getSchedulerWorshipTeamLineupFilterItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/worship/scheduler-teams-lineup/items-teams-lineup/filter',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request){
            return new Response(200, Application\WorshipTeam::getSchedulerWorshipTeamLineupFilterItems($request),'application/json');
        }
    ]);

    $obRouter->get('/application/worship/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function($request, $navTab){
            return new Response(200, Application\WorshipTeam::getConfig($request,$navTab));
        }
    ]);

    $obRouter->get('/application/worship/{id}/search',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-worship-team'
        ],
        function ($request, $id){
            return new Response(200, Application\WorshipTeam::getSearch($request,$id), 'application/json');
        }
    ]);
}