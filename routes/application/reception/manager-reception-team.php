<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {
    //Rota agenda
    $obRouter->get('/application/scheduler-reception-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getEvents($request),'application/json');
        }
    ]);
    //Rota agenda
    $obRouter->get('/application/reception/scheduler-reception-teams-lineup',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getEvents($request),'application/json');
        }
    ]);

    //Rota agenda
//    $obRouter->get('/application/scheduler-teams-lineup/items-teams-lineup',[
//        'middlewares' => [
//            'required-login',
//            'allow-page-manager-sound-team'
//        ],
//        function($request){
//            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupItems($request),'application/json');
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
//            return new Response(200, Application\ReceptionTeam::getSchedulerSoundTeamLineupFilterItems($request),'application/json');
//        }
//    ]);

    //Rota agenda
    $obRouter->get('/application/reception/manager-team-reception/new',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::getNewReceptionTeam($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/manager-team-reception/new',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request){
            return new Response(200, Application\ReceptionTeam::setReceptionTeam($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/manager-team-reception/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeam::getEditReceptionTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/manager-team-reception/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeam::setEditReceptionTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/reception/manager-team-reception/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeam::getDeleteReceptionTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/reception/manager-team-reception/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-manager-reception-team'
        ],
        function($request, $id){
            return new Response(200, Application\ReceptionTeam::setDeleteReceptionTeam($request, $id));
        }
    ]);
}