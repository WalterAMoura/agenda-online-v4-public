<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/sound-team-lineup/new',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup'
        ],
        function($request){
            return new Response(200, Application\SoundTeamLineup::getNewSoundTeamLineup($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/sound-team-lineup/new',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup'
        ],
        function($request){
            return new Response(200, Application\SoundTeamLineup::setSoundTeamLineup($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/sound-team-lineup/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup'
        ],
        function($request, $id){
            return new Response(200, Application\SoundTeamLineup::getEditSoundTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/sound-team-lineup/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup'
        ],
        function($request, $id){
            return new Response(200, Application\SoundTeamLineup::setEditSoundTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/sound-team-lineup/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup'
        ],
        function($request, $id){
            return new Response(200, Application\SoundTeamLineup::getDeleteSoundTeamLineup($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/sound-team-lineup/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-team-lineup'
        ],
        function($request, $id){
            return new Response(200, Application\SoundTeamLineup::setDeleteSoundTeamLineup($request, $id));
        }
    ]);
}