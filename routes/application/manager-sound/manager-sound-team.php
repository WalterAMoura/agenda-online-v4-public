<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/application/scheduler-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getEvents($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/scheduler-teams-lineup/items-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getSchedulerSoundTeamLineupItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/scheduler-teams-lineup/items-teams-lineup/filter',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-sound-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getSchedulerSoundTeamLineupFilterItems($request),'application/json');
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/manager-team/new',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::getNewSoundTeam($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/manager-team/new',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-team'
        ],
        function($request){
            return new Response(200, Application\ManagerSoundTeam_V2::setSoundTeam($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/manager-team/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-team'
        ],
        function($request, $id){
            return new Response(200, Application\ManagerSoundTeam_V2::getEditSoundTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/manager-team/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-team'
        ],
        function($request, $id){
            return new Response(200, Application\ManagerSoundTeam_V2::setEditSoundTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/manager-team/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-team'
        ],
        function($request, $id){
            return new Response(200, Application\ManagerSoundTeam_V2::getDeleteSoundTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/manager-team/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-team'
        ],
        function($request, $id){
            return new Response(200, Application\ManagerSoundTeam_V2::setDeleteSoundTeam($request, $id));
        }
    ]);
}