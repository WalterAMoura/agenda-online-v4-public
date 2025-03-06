<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota config-event
    $obRouter->get('/application/manager-sound-team',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getConfig($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/scheduler-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getSchedulerSoundTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/scheduler-teams-lineup/items-teams-lineup/filter',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getSchedulerSoundTeamLineupFilterItems($request),'application/json');
        }
    ]);

    $obRouter->get('/application/manager-sound-team/{navTab}',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request, $navTab){
            return new Response(200, Application\ManagerSoundTeam_V2::getConfig($request,$navTab));
        }
    ]);
}