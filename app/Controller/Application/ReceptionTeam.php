<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\ReceptionTeam as EntityReceptionTeam;
use App\Model\Entity\ReceptionTeamLineup as EntityReceptionTeamLineup;
use App\Model\Entity\ReceptionAskToChange as EntityReceptionAskToChange;
use App\Session\Users\Login as SessionUsersLogin;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\Pagination;
use App\Utils\View;
use App\Utils\ViewJS;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class ReceptionTeam extends Page
{
    /**
     * @var array
     */
    private static array $modules = [
        'loadTable' => [
            'script' => 'application/js/scripts/table-reception-team-lineup'
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

        //Intera os modulos
        foreach (self::$modules as $hash => $module) {
            $scripts .= ViewJS::render($module['script'],[]);;
        }

        return $scripts;
    }

    /**
     * Método responsável por retornar o script da página de eventos
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
     * Método responsável por renderizar a view do menu de configurações
     * @param string|null $currentTab
     * @return string
     * @throws Exception
     */
    private static function getNavTab(string $currentTab = null): string
    {

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];
        $userId = $session['usuario']['id'];

        //Debug::debug($currentTab);

        //Links do menu
        $links = '';
        $tables = '';
        $tabsPane = '';
        // recupera módulos para o level
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 10','module_id ASC' );
        $i=1;
        $currentModuleId=null;
        $current=null;
        while ($obAccessModules = $result->fetchObject(EntityAccessModules::class)){
            if(!isset($currentTab)){
                $currentModuleId = ($i === 1) ? $obAccessModules->module_id : null;
                $current = ($i === 1) ? 'active' : null;
            }else{
                $currentModuleId = ($obAccessModules->module === $currentTab) ? $obAccessModules->module_id : null;
                $current = ($obAccessModules->module === $currentTab) ? 'active' : null;
            }

            $links .= View::render('application/modules/reception/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current,
                'idNavTab' => str_replace('#','id_',$obAccessModules->path_module)
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/reception/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId),
                'disabledMyAskToChange' => self::getAllowButton($levelId, 98),
                'totalPendente' => self::getPendenteAskToChange($userId)
            ]);

            $tabsPane .= View::render('application/modules/reception/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 133) ? 'active' : (($currentModuleId === 134) ? 'active' : (($currentModuleId === 135) ? 'active' :  null)),
                'tabPaneId' => str_replace("#","",$obAccessModules->path_module),
                'titile' => $obAccessModules->label,
                'navTab' => $obAccessModules->module,
                'disabledCreate' => self::getDisabledCreated($obAccessModules->module,$levelId),
                'btnName' => ($currentModuleId === 133)? 'Definir Escala':'Cadastrar',
                'display' => ($obAccessModules->module_id == 134)? 'none':'block',
                'table' => $tables
            ]);
            $i++;
        }

        // recupera módulos para o level
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 6','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/reception/nav-tab/box', [
            'links' => $links,
            'tabsPane' => $tabsPane
        ]);
    }

    /**
     * @param string $module
     * @param integer $levelId
     * @throws Exception
     * @return string|null
     */
    private static function getDisabledCreated(string $module, int $levelId)
    {
        return match ($module) {
            'my-scheduler' => null,
            'reception-team-lineup' => self::getAllowButton($levelId, 142),
            'manager-team-reception' => self::getAllowButton($levelId, 137),
            default => null,
        };
    }

    /**
     * @param string $module
     * @param integer $levelId
     * @throws Exception
     * @return string|null
     */
    private static function getTables(string $module, int $levelId)
    {
        return match ($module) {
            'my-scheduler-reception' => self::getMySchedulerItems($module),
            'manager-team-reception' => self::getManagerTeamItems($module),
            'reception-team-lineup' => self::getReceptionTeamLineupItems($module),
            default => null,
        };
    }

    /**
     * @param int $userId
     * @return int
     */
    private static function getPendenteAskToChange(int $userId)
    {
        // recupera usuário vinculado a equipe
        $obReceptionTeam = self::getUserReceptionTeam($userId);
        //$id=0;
        $id = !$obReceptionTeam instanceof EntityReceptionTeam ? 0 : $obReceptionTeam->id;
        // busca solicitações de troca com status pendente
        $obAskToChange = EntityReceptionAskToChange::getAskToChange('new_linked_user_id = ' . $id . ' AND  status = 1',null,null,null,'COUNT(*) as total_records')->fetchObject(EntityReceptionAskToChange::class);

        return $obAskToChange->total_records;
    }

    /**
     * @param int $userId
     * @return EntityReceptionTeam|false
     */
    private static function getUserReceptionTeam(int $userId)
    {
        // Obtém do banco de dados
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamByLinkedUserId($userId);

        if(!$obReceptionTeam instanceof EntityReceptionTeam){
            return false;
        }

        return $obReceptionTeam;
    }

    /**
     * Método responsável por autorizar o uso do botão gestão de eventos
     * @return string|null
     * @throws Exception
     */
    private static function getAllowButton($levelId, $moduleId)
    {
        $obAccessModules = EntityAccessModules::getAccessModuleByIdByLevelId($levelId, $moduleId);

        if(!$obAccessModules instanceof EntityAccessModules){
            return 'disabled';
        }

        return ($obAccessModules->allow == 'true' || $obAccessModules->allow == 1)? null:'disabled';
    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getReceptionTeamLineupItems(string $module): string
    {

        // retorna os itens
        return View::render('application/modules/reception/nav-tab/tab-pane/items/'.$module,[
            'tableTeamLineupDay' => self::getReceptionTeamLineupTodayTable()
        ]);

    }

    /**
     * Método responsável por retornar a tabela da escala da agenda de hoje
     * @return string
     * @throws Exception
     */
    private static function getReceptionTeamLineupTodayTable()
    {
        return View::render('application/modules/reception/nav-tab/tab-pane/tables/reception-team-lineup-today',[
            'items' =>  self::getReceptionTeamLineupTodayItems()
        ]);
    }

    /**
     * Método responsável por retornar os items da escala da agenda de hoje
     * @return string
     * @throws Exception
     */
    private static function getReceptionTeamLineupTodayItems()
    {

        // inicializa variável de retorno
        $itens = '';

        $start = new DateTime('now', new DateTimeZone('UTC'));
        $start = $start->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('Y-m-d');

        $end = new DateTime('now', new DateTimeZone('UTC'));
        $end = $end->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format('Y-m-d');

        //Debug::debug($start);

//        $month = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
//        $month = $month->setTimezone(new DateTimeZone('GMT-3'))->format('m');
//
//        $year = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
//        $year = $year->setTimezone(new DateTimeZone('GMT-3'))->format('Y');

        $results = EntityReceptionTeamLineup::getReceptionTeamLineup('DATE_FORMAT(scheduler_date, "%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'"', 'id ASC');
        //Debug::debug($results->fetchObject(EntityReceptionTeamLineup::class));
        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntityReceptionTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens  .= View::render('application/modules/reception/nav-tab/tab-pane/items/reception-team-lineup-today', [
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'who' => $obSoundTeamLineup->completed_name
            ]);
        }
        //Debug::debug($itens);
        // retorna os depoimentos
        return $itens;
    }

    /**
     * @param string $module
     * @param string|null $start
     * @return string
     * @throws Exception
     */
    private static function getMySchedulerItems(string $module, string $start = null): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];
        $userId = $session['usuario']['id'];

        $month = (isset($start))? new DateTime('now', new DateTimeZone('America/Sao_Paulo')) :new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $month = $month->setTimezone(new DateTimeZone('GMT-3'))->format('m');

        $year = (isset($start))? new DateTime('now', new DateTimeZone('America/Sao_Paulo')) :new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $year = $year->setTimezone(new DateTimeZone('GMT-3'))->format('Y');

        $nextYear = (isset($start))? new DateTime('now', new DateTimeZone('America/Sao_Paulo')) :new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        // soma 1 ano se o mês atual for igual a 12
        if($month === '12'){
            $nextYear = $nextYear->add(new DateInterval('P1Y'));
        }
        $nextYear = $nextYear->setTimezone(new DateTimeZone('GMT-3'))->format('Y');

        $currentDate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $currentDate = $currentDate->setTimezone(new DateTimeZone('GMT-3'))->format('d-m-Y');

        // inicializa itens
        $itens = '';
        $order = ' `order_month` ASC, `scheduler_date` ASC';
        $results = EntityReceptionTeamLineup::getReceptionTeamLineup(' year BETWEEN ' . $year . ' AND '. $nextYear . ' AND  linked_user_id = ' . $userId,$order,null, null, '*, DATE_FORMAT(scheduler_date, "%d-%m-%Y") as start, CASE WHEN `month` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Debug::debug($results);

        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntityReceptionTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens .= View::render('application/modules/reception/nav-tab/tab-pane/items/'.$module,[
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'who' => $obSoundTeamLineup->completed_name,
                'month' => $obSoundTeamLineup->month_long_description,
                'disabledEdit' => ( strtotime($currentDate) > strtotime($schedulerDate))? 'disabled' : null,
                'module' => $module,
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getManagerTeamItems(string $module): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // inicializa itens
        $itens = '';

        $results = EntityReceptionTeam::getReceptionTeam(null,'id ASC');
        // renderiza itens
        while ($obReceptionTeam = $results->fetchObject(EntityReceptionTeam::class)){
            $itens .= View::render('application/modules/reception/nav-tab/tab-pane/items/'. $module,[
                'id' => $obReceptionTeam->id,
                'name' => $obReceptionTeam->complete_name,
                'contato' => $obReceptionTeam->phone_mask,
                'email' => $obReceptionTeam->email,
                'module' => $module,
                'disabledEdit' => self::getAllowButton($levelId, 138),
                'disabledRemove' => self::getAllowButton($levelId, 139)
            ]);
        }

        //Debug::debug($itens);

        return $itens;

    }

    /**
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @param string|null $currentTab
     * @return mixed
     * @throws Exception
     */
    public static function getConfig(Request $request, string $currentTab = null): mixed
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        $queryParams = $request->getQueryParams();
        if(isset($queryParams['start']) and isset($queryParams['end'])){
            return json_encode(self::getEvents($request), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            //return self::getEvents($request);
        }else{
            // get type modulo
            $obTypeModulo = EntityTypeModules::getTypeModuleById(10);

            //Conteúdo de página de configuração
            $content = View::render('application/modules/reception/index',[
                'title' => $obOrganization->full_name,
                'description' => $obTypeModulo->description,
                'menuTab' => self::getNavTab($currentTab),
                'status' => self::getStatus($request)
            ]);

            // Obtendo informações sobre a pilha de chamadas
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página da recepção.');

            // Retorna a pagina completa
            return parent::getPanel($obOrganization->full_name . ' | Recepção',$content,'reception', self::getScriptEvent($request));
        }
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
    private static function getEventItems(Request $request): array
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        // inicializa variável de retornar
        $itens = [];
        $queryParams = $request->getQueryParams();

        $start = new DateTime($queryParams['start'], new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('GMT-3'))->format('Y-m-d H:i:s');

        $end = new DateTime($queryParams['end'], new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('GMT-3'))->format('Y-m-d H:i:s');


        $_SESSION[SESSION_NAME]['schduler_team'] = [
            'start' => $start,
            'end' => $end
        ];

        $events = EntityReceptionTeamLineup::getReceptionTeamLineup('DATE_FORMAT(scheduler_date, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"', null,null, 'DATE_FORMAT(scheduler_date, "%Y-%m-%d")','*, DATE_FORMAT(scheduler_date, "%Y-%m-%d") AS scheduler_date, COUNT(DATE_FORMAT(scheduler_date, "%Y-%m-%d")) As title');

        //INSERT INTO `tb_sound_team_schedule`(`id`, `created_at`, `scheduler_date`, `sound_team_id`, `sound_device_id`, `updated_at`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]')
        // renderiza os itens
        while ($obEvent = $events->fetchObject(EntityReceptionTeamLineup::class)){
            $itens[] = [
                'title' => $obEvent->title,
                'color' => ($obEvent->title >= 1)? 'green':'red',
                'textColor' => 'white',
                'start' => $obEvent->scheduler_date,
                'end' => $obEvent->scheduler_date,
                'canceled' => false
            ];
        }

        return $itens;
    }

    /**
     * Método responsável por lista os usuários
     * @param string|null $selected
     * @return string
     */
    private static function getUsers(string $selected = null): string
    {
        // carregar dias da semana
        $options = '';

        $order = 'id ASC';
        $where = 'id_status = 1';
        $results = EntityUser::getUsers($where, $order);
        while ($obUser = $results->fetchObject(EntityUser::class)){
            $options .= View::render('application/modules/reception/forms/select',[
                'optionValue' => $obUser->id,
                'optionName' => $obUser->name,
                'selected' => ($obUser->name === $selected)? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewReceptionTeam(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/reception/forms/manager-team-reception',[
            'title' => 'Cadastrar Equipe',
            'breadcrumbItem' => 'Cadastrar Equipe',
            'completeName' => null,
            'name' => null,
            'telefone' => null,
            'emailAddress' => null,
            'linkedUser' => self::getUsers(),
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Recepção',$content,'reception');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setReceptionTeam(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $completeName = $postVars['completeName'] ?? '';
        $telefone = $postVars['telefone'] ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        $email = $postVars['emailAddress'] ?? '';
        $linkedUser = $postVars['linkedUser'] ?? '';

        if(General::isNullOrEmpty($postVars['name']) && General::isNullOrEmpty($postVars['telefone']) && General::isNullOrEmpty($postVars['completeName']) && General::isNullOrEmpty($postVars['emailAddress'])){
            $request->getRouter()->redirect('/application/reception/manager-team-reception/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamByName($name);
        if($obReceptionTeam instanceof EntityReceptionTeam){
            $request->getRouter()->redirect('/application/reception/manager-team-reception/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obReceptionTeam = new EntityReceptionTeam();
        $obReceptionTeam->complete_name = $completeName;
        $obReceptionTeam->name = $name;
        $obReceptionTeam->contato = $telefone;
        $obReceptionTeam->email = $email;
        $obReceptionTeam->linked_user_id = $linkedUser;
        $obReceptionTeam->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/reception/manager-team-reception/'.$obReceptionTeam->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditReceptionTeam(Request $request, int $id): string
    {
        // Obtém o sonoplasta do banco de dados
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamById($id);

        // Valida instância
        if(!$obReceptionTeam instanceof EntityReceptionTeam){
            $request->getRouter()->redirect('/application/reception/manager-team-reception?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/reception/forms/manager-team-reception',[
            'title' => 'Editar ',
            'breadcrumbItem' => 'Editar ',
            'completeName' =>$obReceptionTeam->complete_name,
            'name' => $obReceptionTeam->name,
            'telefone' => $obReceptionTeam->contato,
            'emailAddress' => $obReceptionTeam->email,
            'linkedUser' => self::getUsers($obReceptionTeam->linked_user_name),
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Recepção',$content,'reception');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditReceptionTeam(Request $request, int $id): array
    {
        // Obtém o sonoplasta do banco de dados
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamById($id);

        // Valida instância
        if(!$obReceptionTeam instanceof EntityReceptionTeam){
            $request->getRouter()->redirect('/application/reception/manager-team-reception?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $completeName = $postVars['completeName'] ?? '';
        $telefone = $postVars['telefone'] ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        $email = $postVars['emailAddress'] ?? '';
        $linkedUser = $postVars['linkedUser'] ?? '';

        if(General::isNullOrEmpty($postVars['name']) && General::isNullOrEmpty($postVars['telefone']) && General::isNullOrEmpty($postVars['completeName']) && General::isNullOrEmpty($postVars['emailAddress'])){
            $request->getRouter()->redirect('/application/reception/manager-team-reception/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamById($id);
        if($obReceptionTeam instanceof EntityReceptionTeam && $obReceptionTeam->id != $id){
            $request->getRouter()->redirect('/application/reception/manager-team-reception/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obReceptionTeam = new EntityReceptionTeam();
        $obReceptionTeam->id = $id;
        $obReceptionTeam->complete_name = $completeName;
        $obReceptionTeam->name = $name;
        $obReceptionTeam->contato = $telefone;
        $obReceptionTeam->email = $email;
        $obReceptionTeam->linked_user_id = $linkedUser;
        $obReceptionTeam->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/reception/manager-team-reception/'.$obReceptionTeam->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteReceptionTeam(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamById($id);

        // Valida instância
        if(!$obReceptionTeam instanceof EntityReceptionTeam){
            $request->getRouter()->redirect('/application/reception/manager-team-reception?status=failed');
        }

        $content = View::render('application/modules/reception/delete/manager-team-reception', [
            'title' => 'Excluir',
            'breadcrumbItem' => 'Excluir',
            'name' => $obReceptionTeam->complete_name,
            'telefone' => $obReceptionTeam->phone_mask,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Recepção',$content,'reception');

    }

    public static function setDeleteReceptionTeam(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obReceptionTeam = EntityReceptionTeam::getReceptionTeamById($id);

        // Valida instância
        if(!$obReceptionTeam instanceof EntityReceptionTeam){
            $request->getRouter()->redirect('/application/reception?status=failed');
        }

        $obReceptionTeam->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/reception?status=deleted');

        return [ "success" => true];
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function getSchedulerSoundTeamLineupItems(Request $request): array
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // inicializa variável de retorno
        $itens = [];
        $queryParams = $request->getQueryParams();

        $start = $queryParams['start'] ?? $session['schduler_team']['start'];
        $end = $queryParams['end'] ?? $session['schduler_team']['end'];

        $start = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $end = new DateTime($end, new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $month = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $month = $month->setTimezone(new DateTimeZone('GMT-3'))->format('m');

        $year = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $year = $year->setTimezone(new DateTimeZone('GMT-3'))->format('Y');

        $results = EntityReceptionTeamLineup::getReceptionTeamLineup('month BETWEEN "'.$month.'" AND "'.$month.'" AND year = ' . $year,'id ASC');
        //Debug::debug($results);
        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntityReceptionTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens[] = [
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'suggestedTime' => $obSoundTeamLineup->suggested_time,
                'who' => $obSoundTeamLineup->completed_name,
                'where' => $obSoundTeamLineup->device,
                'urlEdit' => URL . '/application/reception/reception-team-lineup/'.$obSoundTeamLineup->id.'/edit',
                'urlDelete' => URL . '/application/reception/reception-team-lineup/'.$obSoundTeamLineup->id.'/delete',
                'disabledEdit' => self::getAllowButton($levelId, 140),
                'disabledRemove' => self::getAllowButton($levelId, 141)
            ];
        }

        // retorna os depoimentos
        return [ "eventos" => $itens, "start" => $queryParams['start'] ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function getSchedulerSoundTeamLineupFilterItems(Request $request): array
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // inicializa variável de retorno
        $itens = [];
        $queryParams = $request->getQueryParams();

        $start = new DateTime($queryParams['start'], new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $day = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $day = $day->setTimezone(new DateTimeZone('GMT-3'))->format('d');

        $month = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $month = $month->setTimezone(new DateTimeZone('GMT-3'))->format('m');

        $year = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
        $year = $year->setTimezone(new DateTimeZone('GMT-3'))->format('Y');


        $results = EntityReceptionTeamLineup::getReceptionTeamLineup('day = '.$day.' AND month = "'.$month.'" AND year = ' . $year,'id ASC');
        //Debug::debug($results);
        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntityReceptionTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens[] = [
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'who' => $obSoundTeamLineup->completed_name,
                'urlEdit' => URL . '/application/reception/reception-team-lineup/'.$obSoundTeamLineup->id.'/edit',
                'urlDelete' => URL . '/application/reception/reception-team-lineup/'.$obSoundTeamLineup->id.'/delete',
                'disabledEdit' => self::getAllowButton($levelId, 140),
                'disabledRemove' => self::getAllowButton($levelId, 141)
            ];
        }

        // retorna os depoimentos
        return [ "eventos" => $itens, "start" => $queryParams['start'] ];
    }



    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string|void
     */
    private static function getStatus(Request $request)
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Registro criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Registro atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Registro excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O registro ou descrição digitado já está sendo usado!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar registro!');
                break;
            case 'rejected':
                return Alert::getWarning('Este registro não pode ser apagado, porque já está em uso!');
                break;
        }
    }
}