<?php

namespace App\Controller\Application;

use App\Controller\Application\Charts;
use App\Controller\Application\Alert;
use App\Http\Request;
use App\Model\Entity\AgendaByDepartaments as EntityAgendaByDepartments;
use App\Model\Entity\AgendaByEventStatus as EntityAgendaByEventStatus;
use App\Model\Entity\AgendaByPrograms as EntityAgendaByPrograms;
use App\Model\Entity\Departaments as EntityDepartment;
use App\Model\Entity\Departaments as EntityDepartments;
use App\Model\Entity\Event as EntityEvent;
use App\Model\Entity\EventProgram as EntityEventProgram;
use App\Model\Entity\EventStatus as EntityEventStatus;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\View;
use App\Utils\ViewJS;
use DateTime;
use DateTimeZone;
use Exception;

class Dashboards extends Page
{
    /**
     * @var array|array[]
     */
    private static array $scriptsCharts = [
        'statusEvent' => [
            'script' => 'application/js/scripts/donut',
            'type' => 'event-status',
            'id' => '#chartEventStatus'
        ],
        'program' => [
            'script' => 'application/js/scripts/bar',
            'type' => 'program',
            'id' => '#chartProgram'
        ],
        'department' => [
            'script' => 'application/js/scripts/barI',
            'type' => 'department',
            'id' => '#chartDepartment'
        ]
    ];

    /**
     * Método responsável por retornar os scripts js para gráficos
     * @return string
     * @throws Exception
     */
    private static function getScriptsCharts(): string
    {
        //scripts
        $scripts = '';

        //Navega nos gráficos
        foreach (self::$scriptsCharts as $module) {
            $scripts .= Charts::getCharts($module['type'],$module['script'],$module['id']);
        }

        // retornar os scripts para todas as páginas
        return View::render('application/js/view/script',[
            'scripts' => $scripts
        ]);
    }

    /**
     * @var array
     */
    private static array $modules = [
        'table-selected' => [
            'script' => 'application/js/scripts/table-selected',
            'tableId' => '#tableAllViewHome',
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
                'urlRequest' => URL . '/application/event'
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
     * @param Request $request
     * @return string
     * @throws Exception
     */

    private static function getItemsAgendaByStatus(Request $request)
    {
        $items = '';

        $year = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $year->format('Y');

        $order = 'total DESC';

        $results = EntityAgendaByEventStatus::getAgendaByEventStatus('year = "'. $year. '"',$order);
        while ($obEventStatus = $results->fetchObject(EntityAgendaByEventStatus::class)){
            $items .= View::render('application/modules/dashboards/tbody',[
                'description' => $obEventStatus->status,
                'total' => $obEventStatus->total,
                'id' => $obEventStatus->id,
                'year' => $obEventStatus->year,
                'type' => 'event-status'
            ]);
        }

        return $items;
    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private static function getItemsAgendaByPrograms(Request $request)
    {
        $items = '';

        $year = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $year->format('Y');

        $order = 'total DESC';

        $results = EntityAgendaByPrograms::getAgendaByPrograms('year = "'. $year. '"',$order);
        while ($obProgram = $results->fetchObject(EntityAgendaByPrograms::class)){
            $items .= View::render('application/modules/dashboards/tbody',[
                'description' => $obProgram->description,
                'total' => $obProgram->total,
                'id' => $obProgram->id,
                'year' => $obProgram->year,
                'type' => 'program'
            ]);
        }

        return $items;

    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private static function getItemsAgendaByDepartment(Request $request)
    {
        $items = '';

        $year = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $year->format('Y');

        $order = 'total DESC';

        $results = EntityAgendaByDepartments::getAgendaByDepartments('year = "'. $year. '"',$order);

        while ($obAgendaByDepartment = $results->fetchObject(EntityAgendaByDepartments::class)){
            $items .= View::render('application/modules/dashboards/tbody',[
                'description' => $obAgendaByDepartment->department,
                'total' => $obAgendaByDepartment->total,
                'id' => $obAgendaByDepartment->id,
                'year' => $obAgendaByDepartment->year,
                'type' => 'department'
            ]);
        }

        return $items;
    }

    /**
     * Método responsável por renderizar o gráfico na tela do usuário
     * @param string $id
     * @return string
     */
    private static function getDonutCharts(string $id): string
    {
        return View::render('application/modules/dashboards/charts/donut',[
            'id' => $id
        ]);
    }

    /**
     * Método responsável por renderizar o gráfico na tela do usuário
     * @param string $id
     * @return string
     */
    private static function getBarCharts(string $id): string
    {
        return View::render('application/modules/dashboards/charts/bar',[
            'id' => $id
        ]);
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string|null
     */
    private static function getStatus(Request $request): string|null
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'updated-pwd':
            case 'updated':
                return Alert::getSuccess('Senha atualizada com sucesso!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar senha!');
                break;
        }
        return null;
    }

    /**
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getHome(Request $request): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo da home
        $content = View::render('application/modules/dashboards/index',[
            'status' => self::getStatus($request),
            'itemsEventStatus' => self::getItemsAgendaByStatus($request),
            'chartsEventStatus' => self::getDonutCharts('chartEventStatus'),
            'itemsDepartments' => self::getItemsAgendaByDepartment($request),
            'chartsDepartment' => self::getBarCharts('chartDepartment'),
            'itemsPrograms' => self::getItemsAgendaByPrograms($request),
            'chartsPrograms' => self::getBarCharts('chartProgram')
        ]);

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Dashboards',$content,'dashboards',self::getScriptsCharts());
    }

    /**
     * Método responsável por retornar os eventos filtrados pelos paramentos
     * @param Request $request
     * @param $id
     * @param $year
     * @param $type
     * @return string
     */
    private static function getEvents(Request $request, $id, $year,$type)
    {
        $items= '';
        $where = ($type == 'department') ? 'year_start = "' . $year . '" AND department_id ="' . $id . '"' : (($type == 'program') ? 'year_start = "' . $year . '" AND program_id ="' . $id . '"' : 'year_start = "' . $year . '" AND status_id ="' . $id . '"');
        $order = '`original_start` ASC';

        $results = EntityEvent::getEvents($where, $order, null,null, '*,created_at,id, title, description, color,DATE_FORMAT(start, "%d-%m-%Y") as start, end, contato, phone_mask, hino_inicial, hino_final, status, orador, observacoes, description_status, department, program, month, month_short_description, month_long_description, day_of_week, day_of_week_short_description, day_of_week_long_description');

        while ($obEvent = $results->fetchObject(EntityEvent::class)){
            $items .= View::render('application/modules/dashboards/views/tbody',[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'diaDaSemana' => $obEvent->day_of_week_long_description,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'hinoInicial' => $obEvent->hino_inicial,
                'hinoFinal' => $obEvent->hino_final,
                'orador' => $obEvent->orador,
                'departamento' => $obEvent->department,
                'programacao' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'month' => $obEvent->month_long_description,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'agendado.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/agendado.png' : URL .'/lib/img/confirmado.png')
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
        // status agendamento
        $options = '';
        $order = isset($selected)? 'id ASC': null;
        $results = EntityEventStatus::getStatusEvents(null,$order);
        while ($obStatusEvent = $results->fetchObject(EntityEventStatus::class)){
            $options .= View::render('application/modules/dashboards/select',[
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
        // status agendamento
        $options = '';
        $order = isset($selected)? 'description id': 'description ASC';
        $results = EntityEventProgram::getProgramEvents(null,$order);
        while ($obProgram = $results->fetchObject(EntityEventProgram::class)){
            $options .= View::render('application/modules/dashboards/select',[
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
        // status agendamento
        $options = '';
        $order = isset($selected)? 'department id': 'department ASC';
        $results = EntityDepartment::getDepartments(null,$order);
        while ($obDepartment = $results->fetchObject(EntityDepartment::class)){
            $options .= View::render('application/modules/dashboards/select',[
                'optionValue' => $obDepartment->id,
                'optionName' => $obDepartment->department
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por renderizar o modal
     * @param Request $request
     * @param string $title
     * @return string
     */
    private static function getViewEventModal($request, $title)
    {
        //$modal = '';
        return View::render('application/modules/dashboards/modal-view',[
            'title' => $title,
            'btnTitle' => 'Detalhes Eventos',
            'options' => self::getStatusEvents($request),
            'optPrograms' => self::getProgramEvents($request),
            'optDptos' => self::getDepartments($request)
        ]);
    }

    /**
     * Método responsável por rendrizar a view da home do painel
     * @param Request $request
     * @param $id
     * @param $year
     * @param $type
     * @return string
     * @throws Exception
     */
    public static function getSearchView(Request $request, $id, $year, $type): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        if($type == 'department'){
            $obDepartment = EntityDepartment::getDepartmentById($id);
            $desc = $obDepartment->department;
            $descMain = 'Departamento';
        }elseif ($type == 'event-status') {
            $obEventStatus = EntityEventStatus::getStatusEventById($id);
            $desc = $obEventStatus->description;
            $descMain = 'Eventos Status';
        }else{
            $obProgram = EntityEventProgram::getProgramEventById($id);
            $desc = $obProgram->description;
            $descMain = 'Programações/Eventos Especiais';
        }

        $title = 'Agenda  ' .$year . ' - ' . $desc;
        $mainTitle = 'Agenda '. $descMain;

        //Conteúdo da home
        $content = View::render('application/modules/dashboards/views/index',[
            'mainTitle' => $mainTitle,
            'title'=>$title,
            'itemsView' => self::getEvents($request,$id,$year,$type),
            'modalView' => self::getViewEventModal($request, 'Detalhes Evento')
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página home.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Dashboards',$content,'dashboards', self::getScriptEvent($request));
    }
}