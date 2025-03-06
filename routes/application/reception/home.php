<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/reception',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getConfig($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/scheduler-reception-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/scheduler-reception-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/scheduler-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/scheduler-reception-teams-lineup/items-teams-lineup/filter',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupFilterItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/scheduler-teams-lineup/items-teams-lineup/filter',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupFilterItems($request),'application/json');
        }
    ]);

    $obRouter->get('/application/reception/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request, $navTab){
            return new Response(200, Application\ReceptionTeam::getConfig($request,$navTab));
        }
    ]);
}