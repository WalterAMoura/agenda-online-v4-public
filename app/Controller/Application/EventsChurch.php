<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
//use App\Model\Entity\Event as EntityEvent;
use App\Model\Entity\EventsChurch as EntityEvent;
use App\Model\Entity\EventStatus as EntityEventStatus;
use App\Model\Entity\Departaments as EntityDepartments;
use App\Model\Entity\EventProgram as EntityEventProgram;
use App\Model\Entity\ElderMonthView as EntityElderMonthView;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\TempUser as EntityUserDepartment;
use App\Session\Users\Login as SessionUsersLogin;
use App\Utils\Debug;
use App\Utils\Pagination;
use App\Utils\View;
use App\Utils\ViewJS;
use DateTime;
use DateTimeZone;
use Exception;


class EventsChurch extends Page
{

    /**
     * @var array
     */
    private static array $modules = [
        'loadTable' => [
            'script' => 'application/js/scripts/table-events-church'
        ]
    ];

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @returm string
     */
    private static function getEventItems($request, &$obPagination)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];
        $userId = $session['usuario']['id'];

        // verifica se o usuário é usuário de departamento
        $obUserDepartment = EntityUserDepartment::getUserByUserId($userId);
        //Debug::debug($obUserDepartment);

        // itens
        $itens = '';
        $where = ($obUserDepartment instanceof EntityUserDepartment)? 'department_id = ' . $obUserDepartment->department_id:null;
        $results = EntityEvent::getEvents($where);

        //Renderiza o item
        while ($obEvent = $results->fetchObject(EntityEvent::class)){
            $itens .= View::render('application/modules/events-church/item',[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'contato' => $obEvent->contato,
                'tema' => $obEvent->description,
                'observacoes' => $obEvent->observacoes,
                'orador' => $obEvent->owner,
                'statusEvento' => $obEvent->description_status,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'applicationdo.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'urlImg' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por retornar os scripts js
     * @param Request $request
     * @return string
     */
    private static function getScriptItems(Request $request)
    {
        //scripts
        $scripts = '';

        //Intera os modulos
        foreach (self::$modules as $hash => $module) {
            $scripts .= ViewJS::render($module['script'],[]);;
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

        $obElderMonth = EntityElderMonthView::getElderMonth('month = '.intval($month).' AND year = "'.$year.'"')->fetchObject(EntityElderMonthView::class);

        return $obElderMonth->name;
    }

    /**
     * Método responsável por autorizar o uso do botão gestão de eventos
     * @return string|null
     * @throws Exception
     */
    private static function getAllowManagerEvent()
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        $obAccessModules = EntityAccessModules::getAccessModuleByIdByLevelId($levelId,105);

        if(!$obAccessModules instanceof EntityAccessModules){
            return 'disabled';
        }

        return ($obAccessModules->allow == 'true' || $obAccessModules->allow == 1)? null:'disabled';
    }

    /**
     * Método responsável por rendrizar a view de listagem de eventos
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getEvents($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo de página de usuários
        $content = View::render('application/modules/events-church/index',[
            'itens' => self::getEventItems($request, $obPagination),
            'disabled' => self::getAllowManagerEvent(),
            'acionato' => self::getElderMonth() ?? 'Sem Escala.',
            'loading'=> 'lib/img/loading.gif',
            'modalView' => self::getViewEventModal($request, 'Detalhes Evento'),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de eventos.');

        // Retorna a pagina completa
        return parent::getPanel( $obOrganization->full_name . ' | Agenda',$content,'events-church',self::getScriptEvent($request));
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
        // status agendamento
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
        // status agendamento
        $options = '';
        $order = isset($selected)? 'department id': 'department ASC';
        $results = EntityDepartments::getDepartments('id > 1',$order);
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
            'owner' => $obEvent->owner,
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
     */
    private static function getViewEventModal($request, $title)
    {
        //$modal = '';
        return View::render('application/modules/events-church/modal-view',[
            'title' => $title,
            'btnTitle' => 'Detalhes Eventos',
            'options' => self::getStatusEvents($request),
            'optPrograms' => self::getProgramEvents($request),
            'optDptos' => self::getDepartments($request)
        ]);
    }

    /**
     * Método responsável por renderizar o modal
     * @param Request $request
     * @param string $title
     * @return string
     */
    private static function getNewEventModal($request, $title)
    {
        //$modal = '';
        return View::render('application/modules/events-church/modal',[
            'title' => $title,
            'action' => 'application/events-church',
            'btnType' => 'btn-success',
            'btnTitle' => 'Atualizar Evento',
            'options' => self::getStatusEvents($request),
            'optPrograms' => self::getProgramEvents($request),
            'optDptos' => self::getDepartments($request)
        ]);
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewEvent($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/events-church/calendar',[
            'title' => 'Agendar Novo Evento',
            'breadcrumbItem' => 'Agendar Novo Evento',
            'modal' => self::getNewEventModal($request, 'Novo Evento'),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de eventos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Agenda',$content,'events-church');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewEvent(Request $request): void
    {
        //PostVars
        $postVars = $request->getPostVars();

        $start = $postVars['date'] . ' ' . $postVars['time'];
        $orador = $postVars['orador'] ?? '';
        $telefone = $postVars['telefone'] ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        $tema = $postVars['tema'] ?? '';
        $statusEvent = $postVars['statusEvent'] ?? 1;
        $departamento = $postVars['departamentos'] ?? 1;
        $program = $postVars['programs'] ?? 0;
        $observacoes = $postVars['observacoes'] ?? '';
        $title = $orador . ' - ' . $tema;

        $start = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'));

        // Nova instancia de depoimento
        $obEvent = new EntityEvent();
        $obEvent->title = $title;
        $obEvent->description = $tema;
        $obEvent->start = $start->format('Y-m-d H:i:s');
        $obEvent->end = $start->modify('+1 hours')->format('Y-m-d H:i:s');
        $obEvent->contato = $telefone;
        $obEvent->status_id = $statusEvent;
        $obEvent->owner = $orador;
        $obEvent->observacoes = $observacoes;
        $obEvent->program_id = $program;
        $obEvent->department_id = $departamento;
        $obEvent->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/events-church/home?status=created');
    }

    /**
     * Método responsável por atualizar um evento no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewEditEvent(Request $request): void
    {
        //PostVars
        $postVars = $request->getPostVars();

        $id = $postVars['id'] ?? '';
        $start = $postVars['date'] . ' ' . $postVars['time'];
        $orador = $postVars['orador'] ?? '';
        $telefone = $postVars['telefone'] ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        $tema = $postVars['tema'] ?? '';
        $statusEvent = $postVars['statusEvent'] ?? 1;
        $observacoes = $postVars['observacoes'] ?? '';
        $departamento = $postVars['departamentos'] ?? 1;
        $program = $postVars['programs'] ?? 0;
        $title = $orador . ' - ' . $tema;


        if(!isset($postVars['id'])){
            $request->getRouter()->redirect('/application/events-church/home?status=failed');
        }

        $start = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'));

        // Nova instancia de depoimento
        $obEvent = new EntityEvent();
        $obEvent->id = $id;
        $obEvent->title = $title;
        $obEvent->description = $tema;
        $obEvent->start = $start->format('Y-m-d H:i:s');
        $obEvent->end = $start->modify('+1 hours')->format('Y-m-d H:i:s');
        $obEvent->contato = $telefone;
        $obEvent->status_id = $statusEvent;
        $obEvent->owner = $orador;
        $obEvent->department_id = $departamento;
        $obEvent->program_id = $program;
        $obEvent->observacoes = $observacoes;
        $obEvent->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/events-church/home?status=updated');
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request)
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Evento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Evento atualizado com sucesso!');
                break;
            case 'updated-pwd':
                return Alert::getSuccess('Senha atualizada com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Evento excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail ou login digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar evento!');
                break;
        }
    }


    /**
     * Método responsável por retornar o formulário de exclusão de um evento
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteEvent($request, $id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o evento do banco de dados
        $obEvent= EntityEvent::getEventById($id);

        // Valida instância
        if(!$obEvent instanceof EntityEvent){
            $request->getRouter()->redirect('/application/events-church/home');
        }
        //Conteúdo do formulário
        $content = View::render('application/modules/events-church/delete',[
            'title' => 'Excluir Evento',
            'breadcrumbItem' => 'Excluir Evento',
            'tema' => $obEvent->description,
            'orador' => $obEvent->owner,
            'start' => $obEvent->start
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de eventos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Agenda',$content,'events-church');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @return void
     */
    public static function setDeleteEvent($request, $id)
    {
        // Obtém o evento do banco de dados
        $obEvent= EntityEvent::getEventById($id);

        // Valida instância
        if(!$obEvent instanceof EntityEvent){
            $request->getRouter()->redirect('/application/events-church/home');
        }

        // Excluir o Evento
        //$obEvent->id = $id;
        $obEvent->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/events-church/home?status=deleted');

    }
}