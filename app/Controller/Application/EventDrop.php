<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Event as EntityEvent;
use App\Utils\General;
use DateTime;
use DateTimeZone;
use Exception;

class EventDrop
{

    /**
     * Método responsável por realizar o event drop ou event resize
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public  static function setEventDrop(Request $request): array
    {
        $postVars = $request->getPostVars();

        $start = $postVars['start'] ?? '';
        $end = $postVars['end'] ?? '';
        $id = $postVars['id'] ?? '';

        if(General::isNullOrEmpty(isset($postVars['id'])) or  General::isNullOrEmpty(isset($postVars['start'])) or General::isNullOrEmpty(isset($postVars['end']))){
            $request->getRouter()->redirect('/application/event/home?status=failed');
        }

        // Obtém o evento do banco de dados
        $obEvent= EntityEvent::getEventById($id);

        // Valida instância
        if(!$obEvent instanceof EntityEvent){
            $request->getRouter()->redirect('/application/event/home');
        }

        $start = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'));

        $end = new DateTime($end, new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('UTC'));

        $obEvent = new EntityEvent();
        $obEvent->id = $id;
        $obEvent->start = $start->format('Y-m-d H:i:s');
        $obEvent->end = $end->format('Y-m-d H:i:s');
        $obEvent->updateResizeDrop();

        // retorno de sucesso
        return [ "success" => true];
    }
}