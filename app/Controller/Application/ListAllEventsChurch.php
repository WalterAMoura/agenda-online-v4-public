<?php

namespace App\Controller\Application;

use App\Controller\Session\Session;
use App\Model\Entity\Departaments as EntityDepartments;
use App\Model\Entity\EventsChurch as EntityEvent;
use App\Http\Request;
use App\Model\Entity\EventProgram as EntityEventProgram;
use App\Model\Entity\EventStatus as EntityEventStatus;
use App\Model\Entity\Organization as EntityOrganization;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\TempUser as EntityUserDepartment;
use App\Utils\Debug;
use App\Utils\View;
use App\Utils\ViewJS;
use DateTime;
use DateTimeZone;
use Exception;

class ListAllEventsChurch extends Page
{
    /**
     * @var array
     */
    private static array $modules = [
        'table-selected' => [
            'script' => 'application/js/scripts/table-selected-events-church',
            'tableId' => '#tableAllViewEventsChurch',
            'timeout' => 0
        ]
    ];

    /**
     * Método responsável por retornar os scripts js
     * @param Request $request
     * @return string
     */
    private static function getScriptItems(Request $request)
    {
        //scripts
        $scripts = '';

        //Intera nos scripts
        foreach (self::$modules as $hash => $module) {
            $scripts .= ViewJS::render($module['script'],[
                'tableId' => $module['tableId'],
                'urlRequest' => URL . '/application/events-church'
            ]);;
        }

        return $scripts;
    }

    /**
     * Método responsável por retornar o scritpt da página de eventos
     * @param Request $request
     * @return string
     */
    private static function getScriptEvent(Request $request)
    {
        return View::render('application/js/view/script',[
            'scripts' => self::getScriptItems($request)
        ]);
    }

    /**
     * Método responsável por rendrizar a view da home do painel
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getListAll(Request $request): string
    {
        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $date->setTimezone(new DateTimeZone('UTC'))->format('Y');

        //Conteúdo da home
        $content = View::render('application/modules/events-church/list-all/index',[
            'title' => 'Eventos Ano: ' . $year,
            'items' => self::getEventsAllEvents($request, $year),
            'modalView' => self::getViewEventModal($request, 'Detalhes Evento')
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Agenda',$content,'events-church',self::getScriptEvent($request));
    }

    /**
     * Método responsável por retornar os eventos filtrados pelos paramentos
     * @param Request $request
     * @param int $year
     * @return string
     */
    private static function getEventsAllEvents(Request $request, int $year)
    {

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];
        $userId = $session['usuario']['id'];

        // verifica se o usuário é usuário de departamento
        $obUserDepartment = EntityUserDepartment::getUserByUserId($userId);
        //Debug::debug($obUserDepartment);

        $items= '';
        $where = ($obUserDepartment instanceof EntityUserDepartment)? 'department_id = ' . $obUserDepartment->department_id . ' AND year_start = "' . $year . '"':'year_start = "' . $year . '"';
        $order = 'month ASC, start ASC';

        $results = EntityEvent::getEvents($where, $order, null,null, 'created_at,id, title, description, color,DATE_FORMAT(start, "%d-%m-%Y") as start, end, contato, phone_mask, status, owner, observacoes, description_status, department, program, month, month_short_description, month_long_description, day_of_week, day_of_week_short_description, day_of_week_long_description, elder_id, elder_name, elder_complete_name, elder_phone_mask');

        while ($obEvent = $results->fetchObject(EntityEvent::class)){
            $items .= View::render('application/modules/events-church/list-all/item',[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'diaDaSemana' => $obEvent->day_of_week_long_description,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'orador' => $obEvent->owner,
                'departamento' => $obEvent->department,
                'programacao' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'month' => $obEvent->month_long_description,
                'observacoes' => $obEvent->observacoes,
                'elderId' => $obEvent->elder_id,
                'elderName' => $obEvent->elder_name,
                'elderCompleteName' => $obEvent->elder_complete_name,
                'elderPhoneMask' => $obEvent->elder_phone_mask,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'applicationdo.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'urlImg' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ]);
        }

        return $items;
    }

    /**
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getStatusEvents(Request $request, string $selected = null)
    {
        // status applicationmento
        $options = '';
        $order = isset($selected)? 'id ASC': null;
        $results = EntityEventStatus::getStatusEvents(null,$order);
        while ($obStatusEvent = $results->fetchObject(EntityEventStatus::class)){
            $options .= View::render('application/modules/events-church/select',[
                'optionValue' => $obStatusEvent->id,
                'optionName' => $obStatusEvent->status
            ]);
        }

        return $options;
    }

    /**
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getProgramEvents(Request $request, string $selected = null)
    {
        // status applicationmento
        $options = '';
        $order = isset($selected)? 'description id': 'description ASC';
        $results = EntityEventProgram::getProgramEvents(null,$order);
        while ($obProgram = $results->fetchObject(EntityEventProgram::class)){
            $options .= View::render('application/modules/events-church/select',[
                'optionValue' => $obProgram->id,
                'optionName' => $obProgram->description
            ]);
        }

        return $options;
    }

    /**
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getDepartments(Request $request, string $selected = null)
    {
        // status applicationmento
        $options = '';
        $order = isset($selected)? 'department id': 'department ASC';
        $results = EntityDepartments::getDepartments(null,$order);
        while ($obDepartment = $results->fetchObject(EntityDepartments::class)){
            $options .= View::render('application/modules/events-church/select',[
                'optionValue' => $obDepartment->id,
                'optionName' => $obDepartment->department
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por retornar os eventos de forma paginada
     * @param Request $request
     * @param int $id
     * @return string|array
     * @throws Exception
     */
    private static function getEventItemById(Request $request, int $id): string|array
    {
        // inicializa
        $itens = [];

        $obEvent = EntityEvent::getEventById($id);

        if(!$obEvent instanceof EntityEvent){
            throw new Exception('Nenhum evento encontrado para o id ['. $id .'].',404);
        }

        $itens[] = [
            'id' => (int)$obEvent->id,
            'title' => $obEvent->title,
            'description' => $obEvent->description,
            'color' => $obEvent->color,
            'contato' => $obEvent->contato,
            'status' => $obEvent->description_status,
            'status_id' => $obEvent->status_id,
            'orador' => $obEvent->owner,
            'textColor' => $obEvent->textColor,
            'observacoes' => $obEvent->observacoes,
            'start' => $obEvent->start,
            'end' => $obEvent->end,
            'department' => $obEvent->department,
            'department_id' => $obEvent->department_id,
            'program' => $obEvent->program,
            'program_id' => $obEvent->program_id,
            'elder_id' => $obEvent->elder_id,
            'elder_name' => $obEvent->elder_name,
            'elder_complete_name' => $obEvent->elder_complete_name,
            'elder_phone_mask' => $obEvent->elder_phone_mask,
            'canceled' => $obEvent->description_status === 'CANCELADO'
        ];

        return $itens;
    }

    /**
     * Método responsável por retornar os eventos cadastrados
     * @param Request $request
     * @param int $id
     * @return array
     * @throws Exception
     */
    public static function getEventById(Request $request, int $id) : array
    {
        return [
            'eventos' => self::getEventItemById($request, $id)
        ];
    }

    /**
     * Método responsável por renderizar o modal
     * @param Request $request
     * @param string $title
     * @return string
     * @throws Exception
     */
    private static function getViewEventModal($request, $title)
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de lista eventos.');

        //$modal = '';
        return View::render('application/modules/events-church/modal-view',[
            'title' => $title,
            'btnTitle' => 'Detalhes Eventos',
            'options' => self::getStatusEvents($request),
            'optPrograms' => self::getProgramEvents($request),
            'optDptos' => self::getDepartments($request)
        ]);
    }
}