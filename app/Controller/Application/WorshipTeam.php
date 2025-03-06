<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\Singers as EntitySingers;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\WorshipTeam as EntityWorshipTeam;
use App\Model\Entity\WorshipTeamLineup as EntityWorshipTeamLineup;
use App\Model\Entity\WorshipAskToChange as EntityWorshipAskToChange;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\WorshipTeamLineupV2 as EntityWorshipTeamLineupV2;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\Pagination;
use App\Utils\View;
use App\Utils\ViewJS;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class WorshipTeam extends Page
{
    /**
     * @var array
     */
    private static array $modules = [
        'loadTable' => [
            'script' => 'application/js/scripts/table-worship-team-lineup'
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
     * Método responsável por lista a equipe
     * @param string|null $selected
     * @return string
     */
    private static function getSingers(string $selected = null): string
    {
        // carregar a equipe
        $options = '';

        $names = mb_split(',',$selected);
        $names_final = array();
        //remove os espaços em branco
        foreach ($names as $name){
            $names_final[] = trim($name);
        }

        $order = 'id ASC';
        $where = null;
        $results = EntitySingers::getSigers($where, $order);
        while ($obSingers = $results->fetchObject(EntitySingers::class)){
            $options .= View::render('application/modules/worship/forms/select2',[
                'optionValue' => $obSingers->id,
                'optionName' => $obSingers->singer,
                'selected' => (in_array($obSingers->singer, $names_final))? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por lista a equipe
     * @param string|null $selected
     * @return string
     */
    private static function getWorships(string $selected = null): string
    {
        // carregar a equipe
        $options = '';

        $names = mb_split(',',$selected);
        $names_final = array();
        //remove os espaços em branco
        foreach ($names as $name){
            $names_final[] = trim($name);
        }

        $order = 'id ASC';
        $where = null;
        $results = EntityWorshipTeam::getWorshipTeam($where, $order);
        while ($obWorshipTeam = $results->fetchObject(EntityWorshipTeam::class)){
            $options .= View::render('application/modules/worship/forms/select2',[
                'optionValue' => $obWorshipTeam->id,
                'optionName' => $obWorshipTeam->complete_name,
                'selected' => (in_array($obWorshipTeam->complete_name, $names_final))? 'selected' : null
            ]);
        }
        return $options;
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
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 11','module_id ASC' );
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

            $links .= View::render('application/modules/worship/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current,
                'idNavTab' => str_replace('#','id_',$obAccessModules->path_module)
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/worship/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId),
                'disabledMyAskToChange' => self::getAllowButton($levelId, 98),
                'totalPendente' => self::getPendenteAskToChange($userId)
            ]);

            $tabsPane .= View::render('application/modules/worship/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 145) ? 'active' : (($currentModuleId === 146) ? 'active' : (($currentModuleId === 147) ? 'active' :  null)),
                'tabPaneId' => str_replace("#","",$obAccessModules->path_module),
                'titile' => $obAccessModules->label,
                'navTab' => $obAccessModules->module,
                'disabledCreate' => self::getDisabledCreated($obAccessModules->module,$levelId),
                'btnName' => ($currentModuleId === 145)? 'Definir Escala':'Cadastrar',
                'display' => ($obAccessModules->module_id == 146)? 'none':'block',
                'table' => $tables
            ]);
            $i++;
        }

        // recupera módulos para o level
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 6','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/worship/nav-tab/box', [
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
            'worship-team-lineup' => self::getAllowButton($levelId, 154),
            'manager-team-worship' => self::getAllowButton($levelId, 149),
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
            'my-scheduler-worship' => self::getMySchedulerItems($module),
            'manager-team-worship' => self::getManagerTeamItems($module),
            'worship-team-lineup' => self::getWorshipTeamLineupItems($module),
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
        $obWorshipTeam = self::getUserWorshipTeam($userId);
        //$id=0;
        $id = !$obWorshipTeam instanceof EntityWorshipTeam ? 0 : $obWorshipTeam->id;
        // busca solicitações de troca com status pendente
        $obAskToChange = EntityWorshipAskToChange::getAskToChange('new_linked_user_id = ' . $id . ' AND  status = 1',null,null,null,'COUNT(*) as total_records')->fetchObject(EntityWorshipAskToChange::class);

        return $obAskToChange->total_records;
    }

    /**
     * @param int $userId
     * @return EntityWorshipTeam|false
     */
    private static function getUserWorshipTeam(int $userId)
    {
        // Obtém do banco de dados
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamByLinkedUserId($userId);

        if(!$obWorshipTeam instanceof EntityWorshipTeam){
            return false;
        }

        return $obWorshipTeam;
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
    private static function getWorshipTeamLineupItems(string $module): string
    {

        // retorna os itens
        return View::render('application/modules/worship/nav-tab/tab-pane/items/'.$module,[
            'tableTeamLineupDay' => self::getWorshipTeamLineupTodayTable()
        ]);

    }

    /**
     * Método responsável por retornar a tabela da escala da agenda de hoje
     * @return string
     * @throws Exception
     */
    private static function getWorshipTeamLineupTodayTable()
    {
        return View::render('application/modules/worship/nav-tab/tab-pane/tables/worship-team-lineup-today',[
            'items' =>  self::getWorshipTeamLineupTodayItems()
        ]);
    }

    /**
     * Método responsável por retornar os items da escala da agenda de hoje
     * @return string
     * @throws Exception
     */
    private static function getWorshipTeamLineupTodayItems()
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

        $results = EntityWorshipTeamLineupV2::getWorshipTeamLineup('DATE_FORMAT(scheduler_date, "%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'"', 'id ASC');
        //Debug::debug($results->fetchObject(EntityWorshipTeamLineup::class));
        // renderiza itens
        //Debug::debug($results);
        while ($obWorshipTeamLineup = $results->fetchObject(EntityWorshipTeamLineupV2::class)){
            $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens  .= View::render('application/modules/worship/nav-tab/tab-pane/items/worship-team-lineup-today', [
                'id' => $obWorshipTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obWorshipTeamLineup->day_long_description,
                'who' => $obWorshipTeamLineup->group_complete_names
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
        $results = EntityWorshipTeamLineup::getWorshipTeamLineup(' year BETWEEN ' . $year . ' AND '. $nextYear . ' AND  linked_user_id = ' . $userId,$order,null, null, '*, DATE_FORMAT(scheduler_date, "%d-%m-%Y") as start, CASE WHEN `month` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Debug::debug($results);

        // renderiza itens
        while ($obWorshipTeamLineup = $results->fetchObject(EntityWorshipTeamLineup::class)){
            $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens .= View::render('application/modules/worship/nav-tab/tab-pane/items/'.$module,[
                'id' => $obWorshipTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obWorshipTeamLineup->day_long_description,
                'who' => $obWorshipTeamLineup->completed_name,
                'month' => $obWorshipTeamLineup->month_long_description,
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

        $results = EntityWorshipTeam::getWorshipTeam(null,'id ASC');
        // renderiza itens
        while ($obWorshipTeam = $results->fetchObject(EntityWorshipTeam::class)){
            $itens .= View::render('application/modules/worship/nav-tab/tab-pane/items/'. $module,[
                'id' => $obWorshipTeam->id,
                'name' => $obWorshipTeam->complete_name,
                'contato' => $obWorshipTeam->phone_mask,
                'email' => $obWorshipTeam->email,
                'module' => $module,
                'disabledEdit' => self::getAllowButton($levelId, 150),
                'disabledRemove' => self::getAllowButton($levelId, 151)
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
            $content = View::render('application/modules/worship/index',[
                'title' => $obOrganization->full_name,
                'description' => $obTypeModulo->description,
                'menuTab' => self::getNavTab($currentTab),
                'status' => self::getStatus($request),
                'modalView' => self::getViewEventModal($request, 'Escala Equipe Louvor'),
            ]);

            // Obtendo informações sobre a pilha de chamadas
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página da recepção.');

            // Retorna a pagina completa
            return parent::getPanel($obOrganization->full_name . ' | Louvor',$content,'worship', self::getScriptEvent($request));
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

        $events = EntityWorshipTeamLineup::getWorshipTeamLineup('DATE_FORMAT(scheduler_date, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"', null,null, 'DATE_FORMAT(scheduler_date, "%Y-%m-%d")','*, DATE_FORMAT(scheduler_date, "%Y-%m-%d") AS scheduler_date, COUNT(DATE_FORMAT(scheduler_date, "%Y-%m-%d")) As title');

        //INSERT INTO `tb_sound_team_schedule`(`id`, `created_at`, `scheduler_date`, `sound_team_id`, `sound_device_id`, `updated_at`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]')
        // renderiza os itens
        while ($obEvent = $events->fetchObject(EntityWorshipTeamLineup::class)){
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
            $options .= View::render('application/modules/worship/forms/select',[
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
    public static function getNewWorshipTeam(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/worship/forms/manager-team-worship',[
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
        return parent::getPanel($obOrganization->full_name . ' | Louvor',$content,'worship');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setWorshipTeam(Request $request): array
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
            $request->getRouter()->redirect('/application/worship/manager-team-worship/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamByName($name);
        if($obWorshipTeam instanceof EntityWorshipTeam){
            $request->getRouter()->redirect('/application/worship/manager-team-worship/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obWorshipTeam = new EntityWorshipTeam();
        $obWorshipTeam->complete_name = $completeName;
        $obWorshipTeam->name = $name;
        $obWorshipTeam->contato = $telefone;
        $obWorshipTeam->email = $email;
        $obWorshipTeam->linked_user_id = $linkedUser;
        $obWorshipTeam->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/worship/manager-team-worship/'.$obWorshipTeam->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditWorshipTeam(Request $request, int $id): string
    {
        // Obtém o sonoplasta do banco de dados
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamById($id);

        // Valida instância
        if(!$obWorshipTeam instanceof EntityWorshipTeam){
            $request->getRouter()->redirect('/application/worship/manager-team-worship?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/worship/forms/manager-team-worship',[
            'title' => 'Editar ',
            'breadcrumbItem' => 'Editar ',
            'completeName' =>$obWorshipTeam->complete_name,
            'name' => $obWorshipTeam->name,
            'telefone' => $obWorshipTeam->contato,
            'emailAddress' => $obWorshipTeam->email,
            'linkedUser' => self::getUsers($obWorshipTeam->linked_user_name),
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
        return parent::getPanel($obOrganization->full_name . ' | Louvor',$content,'worship');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditWorshipTeam(Request $request, int $id): array
    {
        // Obtém o sonoplasta do banco de dados
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamById($id);

        // Valida instância
        if(!$obWorshipTeam instanceof EntityWorshipTeam){
            $request->getRouter()->redirect('/application/worship/manager-team-worship?status=failed');
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
            $request->getRouter()->redirect('/application/worship/manager-team-worship/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamById($id);
        if($obWorshipTeam instanceof EntityWorshipTeam && $obWorshipTeam->id != $id){
            $request->getRouter()->redirect('/application/worship/manager-team-worship/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obWorshipTeam = new EntityWorshipTeam();
        $obWorshipTeam->id = $id;
        $obWorshipTeam->complete_name = $completeName;
        $obWorshipTeam->name = $name;
        $obWorshipTeam->contato = $telefone;
        $obWorshipTeam->email = $email;
        $obWorshipTeam->linked_user_id = $linkedUser;
        $obWorshipTeam->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/worship/manager-team-worship/'.$obWorshipTeam->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteWorshipTeam(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamById($id);

        // Valida instância
        if(!$obWorshipTeam instanceof EntityWorshipTeam){
            $request->getRouter()->redirect('/application/worship/manager-team-worship?status=failed');
        }

        $content = View::render('application/modules/worship/delete/manager-team-worship', [
            'title' => 'Excluir',
            'breadcrumbItem' => 'Excluir',
            'name' => $obWorshipTeam->complete_name,
            'telefone' => $obWorshipTeam->phone_mask,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Louvor',$content,'worship');

    }

    public static function setDeleteWorshipTeam(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obWorshipTeam = EntityWorshipTeam::getWorshipTeamById($id);

        // Valida instância
        if(!$obWorshipTeam instanceof EntityWorshipTeam){
            $request->getRouter()->redirect('/application/worship?status=failed');
        }

        $obWorshipTeam->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/worship?status=deleted');

        return [ "success" => true];
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function getSchedulerWorshipTeamLineupItems(Request $request): array
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

        $results = EntityWorshipTeamLineupV2::getWorshipTeamLineup('month BETWEEN "'.$month.'" AND "'.$month.'" AND year = ' . $year,'id ASC');
        //Debug::debug($results);
        // renderiza itens
        while ($obWorshipTeamLineup = $results->fetchObject(EntityWorshipTeamLineupV2::class)){
            $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens[] = [
                'id' => $obWorshipTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obWorshipTeamLineup->day_long_description,
                'suggestedTime' => $obWorshipTeamLineup->suggested_time,
                'who' => $obWorshipTeamLineup->group_complete_names,
                'where' => $obWorshipTeamLineup->device??null,
                'urlEdit' => URL . '/application/worship/worship-team-lineup/'.$obWorshipTeamLineup->id.'/edit',
                'urlDelete' => URL . '/application/worship/worship-team-lineup/'.$obWorshipTeamLineup->id.'/delete',
                'disabledEdit' => self::getAllowButton($levelId, 152),
                'disabledRemove' => self::getAllowButton($levelId, 153),
                'teste' => $obWorshipTeamLineup->singer_music
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
    public static function getSchedulerWorshipTeamLineupFilterItems(Request $request): array
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


        $results = EntityWorshipTeamLineup::getWorshipTeamLineup('day = '.$day.' AND month = "'.$month.'" AND year = ' . $year,'id ASC');
        //Debug::debug($results);
        // renderiza itens
        while ($obWorshipTeamLineup = $results->fetchObject(EntityWorshipTeamLineup::class)){
            $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens[] = [
                'id' => $obWorshipTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obWorshipTeamLineup->day_long_description,
                'who' => $obWorshipTeamLineup->completed_name,
                'urlEdit' => URL . '/application/worship/worship-team-lineup/'.$obWorshipTeamLineup->id.'/edit',
                'urlDelete' => URL . '/application/worship/worship-team-lineup/'.$obWorshipTeamLineup->id.'/delete',
                'disabledEdit' => self::getAllowButton($levelId, 152),
                'disabledRemove' => self::getAllowButton($levelId, 153)
            ];
        }

        // retorna os depoimentos
        return [ "eventos" => $itens, "start" => $queryParams['start'] ];
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
        return View::render('application/modules/worship/modal/modal-worship-team-lineup',[
            'title' => $title,
            'optSingers' => self::getSingers(),
            'optWorships' => self::getWorships(),
        ]);
    }

    /**
     * Método responsável por retornar a escala por um Id de agendamento
     * @param Request $request
     * @param $schedulerId
     * @return array|array[]
     * @throws Exception
     */
    public static function getSearch(Request $request,$schedulerId)
    {
        $items = [];

        $obWorshipTeamLineup = EntityWorshipTeamLineupV2::getWorshipTeamLineupById($schedulerId);
        if(!$obWorshipTeamLineup instanceof EntityWorshipTeamLineupV2){
            return  [ "events" => null, "error" => "Erro ao buscar Id:" . $schedulerId ];
        }

        $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
        $schedulerDate = $schedulerDate->format('Y-m-d');

        $items[] = [
            'id' => $obWorshipTeamLineup->id,
            'created_at' => $obWorshipTeamLineup->created_at,
            'scheduler_date' => $schedulerDate,
            'updated_at' => $obWorshipTeamLineup->updated_at,
            'day_id' => $obWorshipTeamLineup->day_id,
            'day_of_week' => $obWorshipTeamLineup->day_of_week,
            'day_short_description' => $obWorshipTeamLineup->day_short_description,
            'day_long_description' => $obWorshipTeamLineup->day_long_description,
            'day' => $obWorshipTeamLineup->day,
            'month' => $obWorshipTeamLineup->month,
            'month_short_description' => $obWorshipTeamLineup->month_short_description,
            'month_long_description' => $obWorshipTeamLineup->month_long_description,
            'year' => $obWorshipTeamLineup->year,
            'group_complete_names' => $obWorshipTeamLineup->group_complete_names,
            'group_names' => $obWorshipTeamLineup->group_names,
            'group_singer_ids' => $obWorshipTeamLineup->group_singer_ids,
            'group_singer_names' => $obWorshipTeamLineup->group_singer_names,
            'worship_music' => $obWorshipTeamLineup->worship_music,
            'singer_music' => $obWorshipTeamLineup->singer_music
        ];

        return [ "entries" => $items ];

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