<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Departaments as EntityDepartments;
use App\Model\Entity\Elder as EntityElder;
use App\Model\Entity\ElderForDepartment as EntityElderForDepartment;
use App\Model\Entity\ElderMonthView as EntityElderMonthView;
use App\Model\Entity\EventProgram as EntityEventProgram;
use App\Model\Entity\EventStatus as EntityEventStatus;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\SettingsSmtp as EntitySettingsSmtp;
use App\Model\Entity\EmailAlarmes as EntityEmailAlarmes;
use App\Utils\View;
use Exception;

class ConfigEvent extends Page
{
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

        //Debug::debug($currentTab);

        //Links do menu
        $links = '';
        $tables = '';
        $tabsPane = '';
        // recupera módulos para o level
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 4','module_id ASC' );
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

            $links .= View::render('application/modules/config-event/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/config-event/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId)
            ]);

            $tabsPane .= View::render('application/modules/config-event/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 47) ? 'active' : (($currentModuleId === 48) ? 'active' : (($currentModuleId === 49) ? 'active' : (($currentModuleId === 50) ? 'active' : (($currentModuleId === 51) ? 'active' : (($currentModuleId === 106) ? 'active' : null))))),
                'tabPaneId' => str_replace("#","",$obAccessModules->path_module),
                'titile' => $obAccessModules->label,
                'navTab' => $obAccessModules->module,
                'disabledCreate' => self::getDisabledCreated($obAccessModules->module,$levelId),
                'btnName' => 'Cadastrar',
                'table' => $tables
            ]);
            $i++;
        }

        // recupera módulos para o level
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 4','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/config-event/nav-tab/box', [
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
            'status-event' => self::getAllowButton($levelId, 52),
            'departments' => self::getAllowButton($levelId, 53),
            'programs' => self::getAllowButton($levelId, 54),
            'elder' => self::getAllowButton($levelId, 55),
            'elder-month' => self::getAllowButton($levelId, 56),
            'elder-for-department' => self::getAllowButton($levelId, 107),
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
            'status-event' => self::getStatusEventItems($module),
            'departments' => self::getDepartmentsItems($module),
            'programs' => self::getProgramsItems($module),
            'elder' => self::getEldersItems($levelId, $module),
            'elder-month' => self::getElderMonthItems($module),
            'elder-for-department' => self::getElderForDeparmentItems($module),
            default => null,
        };
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
    private static function getElderMonthItems(string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // usuários
        $itens = '';

        // instancia do banco de dados
        $results = EntityElderMonthView::getElderMonth(null,'year ASC, month ASC');
        $i=1;
        // renderiza item
        while ($obElderMonthView = $results->fetchObject(EntityElderMonthView::class)){
            $itens .= View::render('application/modules/config-event/nav-tab/tab-pane/items/elder-month', [
                'id' => $i,
                'names' => $obElderMonthView->name,
                'month' => $obElderMonthView->month_long_description,
                'year' => $obElderMonthView->year,
                'monthId' => $obElderMonthView->month_id,
                'yearId' => $obElderMonthView->year_id,
                'disabledEdit' => self::getAllowButton($levelId, 65),
                'disabledRemove' => self::getAllowButton($levelId, 66),
                'module' => $module
            ]);
            $i++;
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @return string
     * @throws Exception
     * @returm string
     */
    private static function getElderForDeparmentItems(string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // usuários
        $itens = '';

        // instancia do banco de dados
        $results = EntityElderForDepartment::getElderForDepartment();
        // renderiza item
        while ($obElderForDepartment = $results->fetchObject(EntityElderForDepartment::class)){
            $itens .= View::render('application/modules/config-event/nav-tab/tab-pane/items/elder-for-department', [
                'id' => $obElderForDepartment->id,
                'department' => $obElderForDepartment->department_name,
                'departmentDirector' => $obElderForDepartment->department_director,
                'departmentDirectorPhone' => $obElderForDepartment->director_phone_number_mask,
                'elder' => $obElderForDepartment->complete_name,
                'phone' => $obElderForDepartment->phone_mask,
                'disabledEdit' => self::getAllowButton($levelId, 108),
                'disabledRemove' => self::getAllowButton($levelId, 109),
                'module' => $module
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getStatusEventItems(string $module): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // levels
        $itens = '';

        $results = EntityEventStatus::getStatusEvents('id > 0', 'id ASC');

        //Renderiza o item
        while ($obStatusEvents = $results->fetchObject(EntityEventStatus::class)){
            $itens .= View::render('application/modules/config-event/nav-tab/tab-pane/items/status-event',[
                'id' => $obStatusEvents->id,
                'description' => $obStatusEvents->description,
                'color' => mb_strtoupper($obStatusEvents->color),
                'textColor' => mb_strtoupper($obStatusEvents->text_color),
                'status' => $obStatusEvents->status,
                'module' => $module,
                'disabledEdit' => self::getAllowButton($levelId, 57),
                'disabledRemove' => self::getAllowButton($levelId, 58),
            ]);
        }

        // retorna os itens
        return $itens;

    }

    /**
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getDepartmentsItems(string $module): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // tipo módulos
        $itens = '';

        $results = EntityDepartments::getDepartments('id > 0', 'id ASC');

        //Renderiza o item
        while ($obDepartments = $results->fetchObject(EntityDepartments::class)){
            $itens .= View::render('application/modules/config-event/nav-tab/tab-pane/items/departments',[
                'id' => $obDepartments->id,
                'departmentDirector' => $obDepartments->department_director,
                'description' => $obDepartments->department,
                'phoneNumber' => $obDepartments->phone_number_mask,
                'module' => $module,
                'disabledEdit' => self::getAllowButton($levelId, 59),
                'disabledRemove' => self::getAllowButton($levelId, 60),
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
    private static function getProgramsItems(string $module): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // levels
        $itens = '';

        $results = EntityEventProgram::getProgramEvents('id > 0', 'id ASC');

        //Renderiza o item
        while ($obPrograms = $results->fetchObject(EntityEventProgram::class)){
            $itens .= View::render('application/modules/config-event/nav-tab/tab-pane/items/programs',[
                'id' => $obPrograms->id,
                'description' => $obPrograms->description,
                'module' => $module,
                'disabledEdit' => self::getAllowButton($levelId, 61),
                'disabledRemove' => self::getAllowButton($levelId, 62),
            ]);
        }

        // retorna os itens
        return $itens;

    }

    /**
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getEldersItems(int $levelId,string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // access modules
        $itens = '';

        // instancia do banco de dados
        $results = EntityElder::getElders(null,'id ASC');

        // renderiza item
        while ($obElder = $results->fetchObject(EntityElder::class)){
            $itens .= View::render('application/modules/config-event/nav-tab/tab-pane/items/elder', [
                'id' => $obElder->id,
                'completeName' => $obElder->complete_name,
                'elder' => $obElder->name,
                'contato' => $obElder->phone_mask,
                'disabledEdit' => self::getAllowButton($levelId, 63),
                'disabledRemove' => self::getAllowButton($levelId, 64),
                'module' => $module
            ]);
        }

        // retornar itens
        return $itens;
    }

    /**
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @param string|null $currentTab
     * @return string
     * @throws Exception
     */
    public static function getConfig(Request $request, string $currentTab = null): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // get type modulo
        $obTypeModulo = EntityTypeModules::getTypeModuleById(4);

        //Conteúdo de página de configuração
        $content = View::render('application/modules/config-event/index',[
            'title' => $obOrganization->full_name,
            'description' => $obTypeModulo->description,
            'menuTab' => self::getNavTab($currentTab),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de configurações de evento.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Eventos',$content,'config-event');
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