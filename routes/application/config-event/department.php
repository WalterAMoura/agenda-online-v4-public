<?php

use App\Http\Response;
use App\Controller\Application;

if (isset($obRouter)) {

    //Rota config-event novo departamento ou ministério
    $obRouter->get('/application/config-event/departments/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-departments'
        ],
        function($request){
            return new Response(200, Application\Department::getNewDepartment($request));
        }
    ]);

    //Rota cadastrar novo departamento
    $obRouter->post('/application/config-event/departments/new',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-departments'
        ],
        function($request){
            return new Response(200, Application\Department::setNewDepartment($request));
        }
    ]);

    //Rota config-event edit status event
    $obRouter->get('/application/config-event/departments/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-departments'
        ],
        function($request, $id){
            return new Response(200, Application\Department::getEditDepartment($request,$id));
        }
    ]);

    //Rota config-event edit status event
    $obRouter->post('/application/config-event/departments/{id}/edit',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-departments'
        ],
        function($request, $id){
            return new Response(200, Application\Department::setEditDepartment($request,$id));
        }
    ]);

    //Rota config-event delete level
    $obRouter->get('/application/config-event/departments/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-departments'
        ],
        function($request, $id){
            return new Response(200, Application\Department::getDeleteDepartment($request,$id));
        }
    ]);

    //Rota config-event delete level
    $obRouter->post('/application/config-event/departments/{id}/delete',[
        'middlewares' => [
            'required-login',
            'allow-page-config-event',
            'allow-page-departments'
        ],
        function($request, $id){
            return new Response(200, Application\Department::setDeleteDepartment($request,$id));
        }
    ]);
}