<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\ElderMonthView as EntityElderMonthView;
use App\Model\Entity\Event as EntityEvent;
use App\Session\Users\Login as SessionUsersLogin;
use App\Utils\Debug;
use DateTime;
use DateTimeZone;
use Exception;


class TableEvent extends Page
{
    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private static function getEventItems(Request $request): array
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        // usuários
        $itens = [];

        $queryParams = $request->getQueryParams();

        $start = $queryParams['start'] ?? $session['cookie']['start'];
        $end = $queryParams['end'] ?? $session['cookie']['end'];

        //recupera o ancião do mês
        $monthOfElder = self::getElderMonth($start);

        $start = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $end = new DateTime($end, new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $events = EntityEvent::getEvents('DATE_FORMAT(original_start, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"', null,null, null,'created_at,id, title, description, color,DATE_FORMAT(start, "%d-%m-%Y") as start, end, contato, phone_mask, hino_inicial, hino_final, status, orador, observacoes, description_status, department, program');

        //Renderiza o item
        while ($obEvent = $events->fetchObject(EntityEvent::class)){
            $itens[] = [
                'id' => $obEvent->id,
                'start' => $obEvent->start,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'hinoInicial' => $obEvent->hino_inicial,
                'hinoFinal' => $obEvent->hino_final,
                'orador' => $obEvent->orador,
                'departamento' => $obEvent->department,
                'programa' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'url' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ];
        }

        // retorna os depoimentos
        return [ "eventos" => $itens, "monthOfElder" => (is_null($monthOfElder)) ? 'Sem Escala.' : $monthOfElder];

    }

    /**
     * Método responsável por retornar os anciões do mês
     * @param string $start
     * @return string|null
     * @throws Exception
     */
    private static function getElderMonth(string $start)
    {
        $items = [];

        $date = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $month = $date->format('m');
        $year = $date->format('Y');

        $obElderMonth =EntityElderMonthView::getElderMonthByMonth(intval($month),$year);

        if(!$obElderMonth instanceof EntityElderMonthView){
            return null;
        }

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
        //return [ "events" => self::getEventItems($request), "monthOfElder" => "Walter, Zeca" ];
        return self::getEventItems($request);
    }
}