<?php

namespace App\Controller\Api\V1;

use App\Http\Request;
use App\Model\Entity\Event as EntityEvent;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\AskToChange as EntityAskToChange;
use App\Utils\Debug;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class Events extends Api
{


    /**
     * Método responsável por atualizar as solicitações trocas expiradas
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function getEvents(Request $request)
    {
        // recupera dados de troca
        //Debug::debug($request);
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        // inicializa variável de retornar
        $itens = [];
        $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $now->setTimezone(new DateTimeZone('UTC'))->format('Y');
        $start = $year . '-01-01 00:00:00';
        $end = $year . '-12-01 23:59:59';
//        $queryParams = $request->getQueryParams();
//
//        $start = new DateTime($queryParams['start'], new DateTimeZone('America/Sao_Paulo'));
//        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
//
//        $end = new DateTime($queryParams['end'], new DateTimeZone('America/Sao_Paulo'));
//        $end = $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $events = EntityEvent::getEvents('status_id = 3 AND DATE_FORMAT(original_start, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"','original_start DESC');

        // renderiza os itens
        while ($obEvent = $events->fetchObject(EntityEvent::class)){
            $itens[] = [
                'id' => (int)$obEvent->id,
                'title' => $obEvent->title,
                'description' => $obEvent->description,
                'color' => $obEvent->color,
                'contato' => $obEvent->contato,
                'hino_inicial' => $obEvent->hino_inicial,
                'hino_final' => $obEvent->hino_final,
                'status' => $obEvent->description_status,
                'status_id' => $obEvent->status_id,
                'orador' => $obEvent->orador,
                'textColor' => $obEvent->textColor,
                'observacoes' => $obEvent->observacoes,
                'start' => $obEvent->start,
                'end' => $obEvent->end,
                'department' => $obEvent->department,
                'department_id' => $obEvent->department_id,
                'program' => $obEvent->program,
                'program_id' => $obEvent->program_id,
                'canceled' => $obEvent->description_status === 'CANCELADO'
            ];
        }

        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Registros expirados e atualizados ['. (integer)$updateCount . ']', null);

        return $itens;
    }
}