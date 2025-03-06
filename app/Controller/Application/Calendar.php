<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\ElderMonthView as EntityElderMonthView;
use App\Model\Entity\Event as EntityEvent;
use App\Session\Users\Login as SessionUsersLogin;
use DateTime;
use DateTimeZone;
use Exception;

class Calendar extends Page
{
    /**
     * Método responsável por retornar os eventos de forma paginada
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private static function getEventItems(Request $request): array
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        // inicializa variável de retonor
        $itens = [];
        $queryParams = $request->getQueryParams();

        $start = new DateTime($queryParams['start'], new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $end = new DateTime($queryParams['end'], new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $_SESSION[SESSION_NAME]['cookie'] = [
            'start' => $start,
            'end' => $end
        ];

        $events = EntityEvent::getEvents('DATE_FORMAT(original_start, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"', null,null, 'DATE_FORMAT(start, "%Y-%m-%d"), color','description_status,textColor, color, DATE_FORMAT(start, "%Y-%m-%d") AS start, DATE_FORMAT(start, "%Y-%m-%d") AS end, COUNT(DATE_FORMAT(start, "%Y-%m-%d")) As title');

        // renderiza os itens
        while ($obEvent = $events->fetchObject(EntityEvent::class)){
            $itens[] = [
                'title' => $obEvent->title,
                'color' => $obEvent->color,
                'textColor' => $obEvent->textColor,
                'start' => $obEvent->start,
                'end' => $obEvent->end,
                'canceled' => $obEvent->description_status === 'CANCELADO'
            ];
        }

        return $itens;
    }

    /**
     * Método responsável por retornar os anciões do mês
     * @return string|null
     * @throws Exception
     */
    private static function getElderMonth()
    {
        $items = [];

        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $month = $date->format('m');
        $year = $date->format('Y');

        $obElderMonth =EntityElderMonthView::getElderMonthByMonth(intval($month),$year);

        if(!$obElderMonth instanceof EntityElderMonthView){
            return null;
        }

//        $results = EntityElderMonth::getElderMonth('month = '.intval($month).' AND year = "'.$year.'"');
//
//        while ($obElderMonth = $results->fetchObject(EntityElderMonth::class)) {
//            $items[] = $obElderMonth->name;
//        }
//
//        return implode(', ', $items);

        $obElderMonth = EntityElderMonthView::getElderMonth('month = '.intval($month).' AND year = "'.$year.'"')->fetchObject(EntityElderMonthView::class);
        return $obElderMonth->name;
    }

    /**
     * Método responsável por retornar os eventos cadastrados
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function getEvents(Request $request) : array
    {
        return self::getEventItems($request);
    }

    /**
     * Método responsável por retornar os eventos de forma paginada
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private static function getCompleteEventItems(Request $request): array
    {
        // inicializa variável de retornar
        $itens = [];
        $queryParams = $request->getQueryParams();

        $start = new DateTime($queryParams['start'], new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $end = new DateTime($queryParams['end'], new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $events = EntityEvent::getEvents('DATE_FORMAT(original_start, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"','id DESC');

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

        return $itens;
    }

    /**
     * Método responsável por retornar os eventos cadastrados
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function getEventsComplete(Request $request): array
    {
        return self::getCompleteEventItems($request);
    }
}