<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/sound-device/new',[
        'middlewares' => [
            'required-login',
            'allow-page-device-sound'
        ],
        function($request){
            return new Response(200, Application\SoundDevice::getNewSoundDevice($request));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/sound-device/new',[
        'middlewares' => [
            'required-login',
            'allow-page-device-sound'
        ],
        function($request){
            return new Response(200, Application\SoundDevice::setSoundDevice($request));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/sound-device/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-device-sound'
        ],
        function($request, $id){
            return new Response(200, Application\SoundDevice::getEditSoundTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/sound-device/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-device-sound'
        ],
        function($request, $id){
            return new Response(200, Application\SoundDevice::setEditSoundTeam($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->get('/application/manager-sound-team/sound-device/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-device-sound'
        ],
        function($request, $id){
            return new Response(200, Application\SoundDevice::getDeleteSoundDevice($request, $id));
        }
    ]);

    //Rota agenda
    $obRouter->post('/application/manager-sound-team/sound-device/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-device-sound'
        ],
        function($request, $id){
            return new Response(200, Application\SoundDevice::setDeleteSoundDevice($request, $id));
        }
    ]);
}