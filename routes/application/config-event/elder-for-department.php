<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-event novo level
    $obRouter->get('/application/config-event/elder-for-department/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-for-department'
        ],
        function($request){
            return new Response(200, Application\ElderForDepartment::getNewElderForDepartment($request));
        }
    ]);

    //Rota cadastrar novo status event
    $obRouter->post('/application/config-event/elder-for-department/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-for-department'
        ],
        function($request){
            return new Response(200, Application\ElderForDepartment::setNewElderForDepartment($request));
        }
    ]);

    //Rota config-event edit status event
    $obRouter->get('/application/config-event/elder-for-department/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-for-department'
        ],
        function($request, $id){
            return new Response(200, Application\ElderForDepartment::getEditElderForDepartment($request,$id));
        }
    ]);

    //Rota config-event edit status event
    $obRouter->post('/application/config-event/elder-for-department/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-for-department'
        ],
        function($request, $id){
            return new Response(200, Application\ElderForDepartment::setEditElderForDepartment($request,$id));
        }
    ]);

    //Rota config-event delete level
    $obRouter->get('/application/config-event/elder-for-department/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-for-department'
        ],
        function($request, $id){
            return new Response(200, Application\ElderForDepartment::getDeleteElderForDepartment($request,$id));
        }
    ]);

    //Rota config-event delete level
    $obRouter->post('/application/config-event/elder-for-department/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-elder-for-department'
        ],
        function($request, $id){
            return new Response(200, Application\ElderForDepartment::setDeleteElderForDepartment($request,$id));
        }
    ]);
}