<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\AskToChange as EntityAskToChange;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SoundDevice as EntitySoundDevice;
use App\Model\Entity\SoundTeam as EntitySoundTeam;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\SuggestedTime as EntitySuggestedTime;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Model\Entity\User as EntityUser;
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

class ManagerSoundTeam_V2 extends Page
{
    /**
     * @var array
     */
    private static array $modules = [
        'loadTable' => [
            'script' => 'application/js/scripts/table-team-lineup'
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
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 6','module_id ASC' );
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

            $links .= View::render('application/modules/manager-sound-team/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current,
                'idNavTab' => str_replace('#','id_',$obAccessModules->path_module)
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/manager-sound-team/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId),
                'disabledMyAskToChange' => self::getAllowButton($levelId, 98),
                'totalPendente' => self::getPendenteAskToChange($userId)
            ]);

            $tabsPane .= View::render('application/modules/manager-sound-team/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 72) ? 'active' : (($currentModuleId === 73) ? 'active' : (($currentModuleId === 74) ? 'active' : (($currentModuleId === 75) ? 'active' : (($currentModuleId === 76) ? 'active' : (($currentModuleId === 77) ? 'active' : null))))),
                'tabPaneId' => str_replace("#","",$obAccessModules->path_module),
                'titile' => $obAccessModules->label,
                'navTab' => $obAccessModules->module,
                'disabledCreate' => self::getDisabledCreated($obAccessModules->module,$levelId),
                'btnName' => ($currentModuleId === 72)? 'Definir Escala':'Cadastrar',
                'display' => ($obAccessModules->module_id == 73)? 'none':'block',
                'table' => $tables
            ]);
            $i++;
        }

        // recupera módulos para o level
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 6','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/manager-sound-team/nav-tab/box', [
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
        switch ($module){
            case 'sound-team-lineup':
                return self::getAllowButton($levelId, 78);
                break;
            case 'my-scheduler':
                return null;
                //return self::getAllowButton($levelId, 53);
                break;
            case 'manager-team':
                return self::getAllowButton($levelId, 79);
                break;
            case 'sound-device':
                return self::getAllowButton($levelId, 80);
                break;
            case 'suggested-time':
                return self::getAllowButton($levelId, 81);
                break;
            default:
                return null;
        }
    }

    /**
     * @param string $module
     * @param integer $levelId
     * @throws Exception
     * @return string|null
     */
    private static function getTables(string $module, int $levelId)
    {
        switch ($module){
            case 'sound-team-lineup':
                //return null;
                return self::getSoundTeamLineupItems($module);
                break;
            case 'my-scheduler':
                return self::getMySchedulerItems($module);
                break;
            case 'manager-team':
                return self::getManagerTeamItems($module);
                break;
            case 'sound-device':
                return self::getSoundDeviceItems($levelId,$module);
                break;
            case 'suggested-time':
                return self::getSuggestedTimeItems($module);
                break;
            default:
                return null;
        }
    }

    /**
     * @param int $userId
     * @return int
     */
    private static function getPendenteAskToChange(int $userId)
    {
        // recupera usuário vinculado a equipe
        $obSoundTeam = self::getUserSoundTeam($userId);
        //$id=0;
        $id = !$obSoundTeam instanceof EntitySoundTeam ? 0 : $obSoundTeam->id;
        // busca solicitações de troca com status pendente
        $obAskToChange = EntityAskToChange::getAskToChange('new_linked_user_id = ' . $id . ' AND  status = 1',null,null,null,'COUNT(*) as total_records')->fetchObject(EntityAskToChange::class);

        return $obAskToChange->total_records;
    }

    /**
     * @param int $userId
     * @return EntitySoundTeam|false
     */
    private static function getUserSoundTeam(int $userId)
    {
        // Obtém do banco de dados
        $obSoundTeam = EntitySoundTeam::getSoundTeamByLinkedUserId($userId);

        if(!$obSoundTeam instanceof EntitySoundTeam){
            return false;
        }

        return $obSoundTeam;
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
     * @return string
     * @throws Exception
     * @returm string
     */
    private static function getSuggestedTimeItems(string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // inicializa itens
        $itens = '';

        $results = EntitySuggestedTime::getSuggestedTime(null,'id ASC');
        // renderiza itens
        while ($obSuggestedTime = $results->fetchObject(EntitySuggestedTime::class)){
            $itens .= View::render('application/modules/manager-sound-team/nav-tab/tab-pane/items/suggested-time',[
                'id' => $obSuggestedTime->id,
                'dayOfWeek' => $obSuggestedTime->long_description,
                'suggestedTime' => $obSuggestedTime->suggested_time,
                'disabledEdit' => self::getAllowButton($levelId, 86),
                'disabledRemove' => self::getAllowButton($levelId, 87),
                'module' => $module
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
    private static function getSoundTeamLineupItems(string $module): string
    {

        // retorna os itens
        return View::render('application/modules/manager-sound-team/nav-tab/tab-pane/items/sound-team-lineup',[
            'tableTeamLineupDay' => self::getSoundTeamLineupTodayTable()
        ]);

    }

    /**
     * Método responsável por retornar a tabela da escala da agenda de hoje
     * @return string
     * @throws Exception
     */
    private static function getSoundTeamLineupTodayTable()
    {
        return View::render('application/modules/manager-sound-team/nav-tab/tab-pane/tables/sound-team-lineup-today',[
            'items' =>  self::getSoundTeamLineupTodayItems()
        ]);
    }

    /**
     * Método responsável por retornar os items da escala da agenda de hoje
     * @return string
     * @throws Exception
     */
    private static function getSoundTeamLineupTodayItems()
    {

        // inicializa variável de retorno
        $itens = '';

        $start = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $start = $start->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d');

        $end = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $end = $end->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d');

        //Debug::debug($start);

//        $month = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
//        $month = $month->setTimezone(new DateTimeZone('GMT-3'))->format('m');
//
//        $year = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
//        $year = $year->setTimezone(new DateTimeZone('GMT-3'))->format('Y');

        $results = EntitySoundTeamLineup::getSoundTeamLineup('DATE_FORMAT(scheduler_date, "%Y-%m-%d") BETWEEN "'.$start.'" AND "'.$end.'"', 'id ASC');

        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntitySoundTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens  .= View::render('application/modules/manager-sound-team/nav-tab/tab-pane/items/sound-team-lineup-today', [
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'suggestedTime' => $obSoundTeamLineup->suggested_time,
                'who' => $obSoundTeamLineup->completed_name,
                'where' => $obSoundTeamLineup->device
            ]);
        }

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
        $results = EntitySoundTeamLineup::getSoundTeamLineup(' year BETWEEN ' . $year . ' AND '. $nextYear . ' AND  linked_user_id = ' . $userId,$order,null, null, '*, DATE_FORMAT(scheduler_date, "%d-%m-%Y") as start, CASE WHEN `month` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Debug::debug($results);

        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntitySoundTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens .= View::render('application/modules/manager-sound-team/nav-tab/tab-pane/items/my-scheduler',[
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'suggestedTime' => $obSoundTeamLineup->suggested_time,
                'who' => $obSoundTeamLineup->completed_name,
                'where' => $obSoundTeamLineup->device,
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

        $results = EntitySoundTeam::getSoundTeam(null,'id ASC');
        // renderiza itens
        while ($obSoundTeam = $results->fetchObject(EntitySoundTeam::class)){
            $itens .= View::render('application/modules/manager-sound-team/nav-tab/tab-pane/items/manager-team',[
                'id' => $obSoundTeam->id,
                'name' => $obSoundTeam->complete_name,
                'contato' => $obSoundTeam->phone_mask,
                'email' => $obSoundTeam->email,
                'module' => $module,
                'disabledEdit' => self::getAllowButton($levelId, 82),
                'disabledRemove' => self::getAllowButton($levelId, 83)
            ]);
        }

        return $itens;

    }

    /**
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getSoundDeviceItems(int $levelId,string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // inicializa itens
        $itens = '';

        $results = EntitySoundDevice::getSoundDevice(null,'id ASC');
        // renderiza itens
        while ($obSoundDevice = $results->fetchObject(EntitySoundTeam::class)){
            $itens .= View::render('application/modules/manager-sound-team/nav-tab/tab-pane/items/sound-device',[
                'id' => $obSoundDevice->id,
                'soundDevice' => $obSoundDevice->device,
                'disabledEdit' => self::getAllowButton($levelId, 84),
                'disabledRemove' => self::getAllowButton($levelId, 85),
                'module' => $module
            ]);
        }

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
            $obTypeModulo = EntityTypeModules::getTypeModuleById(6);

            //Conteúdo de página de configuração
            $content = View::render('application/modules/manager-sound-team/index',[
                'title' => $obOrganization->full_name,
                'description' => $obTypeModulo->description,
                'menuTab' => self::getNavTab($currentTab),
                'status' => self::getStatus($request)
            ]);

            // Obtendo informações sobre a pilha de chamadas
            $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página da sonoplastia.');

            // Retorna a pagina completa
            return parent::getPanel($obOrganization->full_name . ' | Sonoplastia',$content,'manager-sound-team', self::getScriptEvent($request));
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

        $events = EntitySoundTeamLineup::getSoundTeamLineup('DATE_FORMAT(scheduler_date, "%Y-%m-%d %H:%i:%s") BETWEEN "'.$start.'" AND "'.$end.'"', null,null, 'DATE_FORMAT(scheduler_date, "%Y-%m-%d")','*, DATE_FORMAT(scheduler_date, "%Y-%m-%d") AS scheduler_date, COUNT(DATE_FORMAT(scheduler_date, "%Y-%m-%d")) As title');

        //INSERT INTO `tb_sound_team_schedule`(`id`, `created_at`, `scheduler_date`, `sound_team_id`, `sound_device_id`, `updated_at`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]')
        // renderiza os itens
        while ($obEvent = $events->fetchObject(EntitySoundTeamLineup::class)){
            $itens[] = [
                'title' => $obEvent->title,
                'color' => ($obEvent->title >= 1 and $obEvent->title <=2 )? 'orange':'green',
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
            $options .= View::render('application/modules/manager-sound-team/forms/select',[
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
    public static function getNewSoundTeam(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/manager-team',[
            'title' => 'Cadastrar Sonoplasta',
            'breadcrumbItem' => 'Cadastrar Sonoplasta',
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
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de sonoplastia.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Sonoplastia',$content,'manager-sound-team');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setSoundTeam(Request $request): array
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
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obSoundTeam = EntitySoundTeam::getSoundTeamByName($name);
        if($obSoundTeam instanceof EntitySoundTeam){
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obSoundTeam = new EntitySoundTeam();
        $obSoundTeam->complete_name = $completeName;
        $obSoundTeam->name = $name;
        $obSoundTeam->contato = $telefone;
        $obSoundTeam->email = $email;
        $obSoundTeam->linked_user_id = $linkedUser;
        $obSoundTeam->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/manager-team/'.$obSoundTeam->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditSoundTeam(Request $request, int $id): string
    {
        // Obtém o sonoplasta do banco de dados
        $obSoundTeam = EntitySoundTeam::getSoundTeamById($id);

        // Valida instância
        if(!$obSoundTeam instanceof EntitySoundTeam){
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/manager-team',[
            'title' => 'Editar Sonoplasta',
            'breadcrumbItem' => 'Editar Sonoplasta',
            'completeName' =>$obSoundTeam->complete_name,
            'name' => $obSoundTeam->name,
            'telefone' => $obSoundTeam->contato,
            'emailAddress' => $obSoundTeam->email,
            'linkedUser' => self::getUsers($obSoundTeam->linked_user_name),
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de sonoplastia.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Sonoplastia',$content,'manager-sound-team');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditSoundTeam(Request $request, int $id): array
    {
        // Obtém o sonoplasta do banco de dados
        $obSoundTeam = EntitySoundTeam::getSoundTeamById($id);

        // Valida instância
        if(!$obSoundTeam instanceof EntitySoundTeam){
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team?status=failed');
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
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team/new?status=failed');
        }

        // valida se o sonoplasta já existe já existe
        $obSoundTeam = EntitySoundTeam::getSoundTeamByName($name);
        if($obSoundTeam instanceof EntitySoundTeam && $obSoundTeam->id != $id){
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team/new?status=duplicated');
        }

        // Nova instancia de sonoplasta
        $obSoundTeam = new EntitySoundTeam();
        $obSoundTeam->id = $id;
        $obSoundTeam->complete_name = $completeName;
        $obSoundTeam->name = $name;
        $obSoundTeam->contato = $telefone;
        $obSoundTeam->email = $email;
        $obSoundTeam->linked_user_id = $linkedUser;
        $obSoundTeam->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/manager-team/'.$obSoundTeam->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteSoundTeam(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obSoundTeam = EntitySoundTeam::getSoundTeamById($id);

        // Valida instância
        if(!$obSoundTeam instanceof EntitySoundTeam){
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team?status=failed');
        }

        $content = View::render('application/modules/manager-sound-team/delete/manager-team', [
            'title' => 'Excluir Sonoplasta',
            'breadcrumbItem' => 'Excluir Sonoplasta',
            'name' => $obSoundTeam->complete_name,
            'telefone' => $obSoundTeam->phone_mask,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de sonoplastia.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Sonoplastia',$content,'manager-sound-team');

    }

    public static function setDeleteSoundTeam(Request $request, int $id)
    {
        // Obtém o sonoplasta do banco de dados
        $obSoundTeam = EntitySoundTeam::getSoundTeamById($id);

        // Valida instância
        if(!$obSoundTeam instanceof EntitySoundTeam){
            $request->getRouter()->redirect('/application/manager-sound-team/manager-team?status=failed');
        }

        $obSoundTeam->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/manager-team?status=deleted');

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

        $results = EntitySoundTeamLineup::getSoundTeamLineup('month BETWEEN "'.$month.'" AND "'.$month.'" AND year = ' . $year,'id ASC');

        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntitySoundTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens[] = [
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'suggestedTime' => $obSoundTeamLineup->suggested_time,
                'who' => $obSoundTeamLineup->completed_name,
                'where' => $obSoundTeamLineup->device,
                'urlEdit' => URL . '/application/manager-sound-team/sound-team-lineup/'.$obSoundTeamLineup->id.'/edit',
                'urlDelete' => URL . '/application/manager-sound-team/sound-team-lineup/'.$obSoundTeamLineup->id.'/delete',
                'disabledEdit' => self::getAllowButton($levelId, 88),
                'disabledRemove' => self::getAllowButton($levelId, 89)
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


        $results = EntitySoundTeamLineup::getSoundTeamLineup('day = '.$day.' AND month = "'.$month.'" AND year = ' . $year,'id ASC');

        // renderiza itens
        while ($obSoundTeamLineup = $results->fetchObject(EntitySoundTeamLineup::class)){
            $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens[] = [
                'id' => $obSoundTeamLineup->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obSoundTeamLineup->day_long_description,
                'suggestedTime' => $obSoundTeamLineup->suggested_time,
                'who' => $obSoundTeamLineup->completed_name,
                'where' => $obSoundTeamLineup->device,
                'urlEdit' => URL . '/application/manager-sound-team/sound-team-lineup/'.$obSoundTeamLineup->id.'/edit',
                'urlDelete' => URL . '/application/manager-sound-team/sound-team-lineup/'.$obSoundTeamLineup->id.'/delete',
                'disabledEdit' => self::getAllowButton($levelId, 88),
                'disabledRemove' => self::getAllowButton($levelId, 89)
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