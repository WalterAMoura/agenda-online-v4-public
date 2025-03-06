<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\Visitor as EntityVisitor;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\SettingsSmtp as EntitySettingsSmtp;
use App\Model\Entity\EmailAlarmes as EntityEmailAlarmes;
use App\Model\Entity\ApiKey as EntityApiKey;
use App\Model\Entity\TempUser as EntityTempUsers;
use App\Model\Entity\AccessTokenWhatsApp as EntityAccessTokenWhatsApp;
use App\Utils\Debug;
use App\Utils\Pagination;
use App\Utils\View;
use App\Model\Entity\Level as EntityLevel;
use App\Model\Entity\ViewAccessModules as EntityViewAccessModules;
use App\Model\Entity\Modules as EntityModules;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class AdvancedSettings extends Page
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
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 5','module_id ASC' );
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

            $links .= View::render('application/modules/advanced-settings/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/advanced-settings/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId)
            ]);

            $tabsPane .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 13) ? 'active' : (($currentModuleId === 14) ? 'active' : (($currentModuleId === 15) ? 'active' : (($currentModuleId === 16) ? 'active' : (($currentModuleId === 27) ? 'active' : (($currentModuleId === 28) ? 'active' : (($currentModuleId === 33) ? 'active' : (($currentModuleId === 38) ? 'active' : (($currentModuleId === 96) ? 'active' : (($currentModuleId === 111) ? 'active' :  (($currentModuleId === 118) ? 'active' : (($currentModuleId === 123) ? 'active' : null ) ) ))))))))),
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
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 5','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/advanced-settings/nav-tab/box', [
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
            'level' => self::getAllowButton($levelId, 19),
            'access-modules' => self::getAllowButton($levelId, 21),
            'users' => self::getAllowButton($levelId, 4),
            'smtp-settings' => self::getAllowButton($levelId, 29),
            'email-alert-configuration' => self::getAllowButton($levelId, 35),
            'apikey' => self::getAllowButton($levelId, 40),
            'temp-users' => self::getAllowButton($levelId, 112),
            'organization' => self::getAllowButton($levelId, 119),
            'whatsapp' => self::getAllowButton($levelId, 124),
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
            'level' => self::getLevelItems($module),
            'type-module' => self::getTypeModules($module),
            'modules' => self::getModulesItems($module),
            'access-modules' => self::getAccessModules($levelId, $module),
            'users' => self::getUserItems($module),
            'smtp-settings' => self::getSmtpItems($module),
            'email-alert-configuration' => self::getEmailAlarmesItems($levelId, $module),
            'apikey' => self::getApikeyItems($levelId, $module),
            'temp-users' => self::getTempUsersItems($levelId, $module),
            'organization' => self::getOrganizationItems($levelId,$module),
            'whatsapp' => self::getWhasAppItems($levelId,$module),
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
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getTempUsersItems(int $levelId,string $module)
    {
        // itens
        $itens = '';

        $results = EntityTempUsers::getTempUsers('id_status <> 4');

        //Renderiza o item
        while ($obTempUsers = $results->fetchObject(EntityTempUsers::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/temp-users',[
                'id' => $obTempUsers->id,
                'name' => $obTempUsers->name,
                'email' => $obTempUsers->email,
                'login' => $obTempUsers->login,
                'department' => $obTempUsers->department,
                'departmentDirector' => $obTempUsers->department_director,
                'departmentDirectorPhone' => $obTempUsers->phone_number_mask,
                'statusUser' => $obTempUsers->status_user,
                'disabledEdit' => self::getAllowButton($levelId, 113),
                'disabledRemove' => ($obTempUsers->id_status == 1 or $obTempUsers->id_status == 5) ? 'disabled' : self::getAllowButton($levelId, 114),
                'disabledApproved' => ($obTempUsers->id_status == 1 or $obTempUsers->id_status == 5) ? 'disabled' : self::getAllowButton($levelId, 113),
                'disabledReproved' => ($obTempUsers->id_status == 1 or $obTempUsers->id_status == 5) ? 'disabled' : self::getAllowButton($levelId, 114),
                'module' => $module
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getEmailAlarmesItems(int $levelId,string $module)
    {
        // itens
        $itens = '';

        $results = EntityEmailAlarmes::getEmailAlarmes();

        //Renderiza o item
        while ($obEmailAlarmes = $results->fetchObject(EntityEmailAlarmes::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/email-alert-configuration',[
                'id' => $obEmailAlarmes->id,
                'name' => $obEmailAlarmes->name,
                'email' => $obEmailAlarmes->email,
                'statusVerified' => $obEmailAlarmes->status_verified,
                'statusEmail' => $obEmailAlarmes->description,
                'disabledEdit' => self::getAllowButton($levelId, 36),
                'disabledRemove' => self::getAllowButton($levelId, 37),
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
    private static function getSmtpItems(string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

         // itens
        $itens = '';

        $results = EntitySettingsSmtp::getSettingsSmtp();

        //Renderiza o item
        while ($obSettingsSmtp = $results->fetchObject(EntitySettingsSmtp::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/smtp-settings',[
                'id' => $obSettingsSmtp->id,
                'host' => $obSettingsSmtp->host,
                'port' => $obSettingsSmtp->port,
                'username' => $obSettingsSmtp->username,
                'apikey' => $obSettingsSmtp->api_key,
                'statusSmtp' => $obSettingsSmtp->status_description,
                'disabledEdit' => self::getAllowButton($levelId, 30),
                'disabledRemove' => self::getAllowButton($levelId, 31),
                'module' => $module
            ]);
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
    private static function getUserItems(string $module)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // usuários
        $itens = '';

        $results = EntityUser::getUsers('id_status <> 4 AND id_nivel >= '. $levelId, 'id ASC');

        //Renderiza o item
        while ($obUser = $results->fetchObject(EntityUser::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/users',[
                'onOffUser' => self::checkOnlineUser($obUser->id),
                'id' => $obUser->id,
                'nome' => $obUser->name,
                'login' => $obUser->login,
                'email' => $obUser->email,
                'nivel' => $obUser->level_description,
                'statusUser' => $obUser->status_user,
                'idLinkResetPass' => $obUser->id,
                'disabledEdit' => self::getAllowButton($levelId, 5),
                'disabledRemove' => self::getAllowButton($levelId, 6),
                'disabledResetPass' => self::getAllowButton($levelId, 5),
                'disabledResendAccountActivate' => ($obUser->id_status === 1)? 'disabled' : null,
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
    private static function getLevelItems(string $module): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // levels
        $itens = '';

        $results = EntityLevel::getLevels('id >= ' . $levelId . ' AND id <> 0', 'id ASC');

        //Renderiza o item
        while ($obLevels = $results->fetchObject(EntityLevel::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/level',[
                'id' => $obLevels->id,
                'description' => $obLevels->description,
                'level' => $obLevels->level,
                'homePath' => $obLevels->home_path,
                'disabledEdit' => self::getAllowButton($levelId, 17),
                'disabledRemove' => self::getAllowButton($levelId, 18),
                'module' => $module
            ]);
        }

        // retorna os itens
        return $itens;

    }

    /**
     * @param string $module
     * @return string
     */
    private static function getTypeModules(string $module): string
    {
        // tipo módulos
        $itens = '';

        $results = EntityTypeModules::getTypeModules('id > 0');
        // renderiza itens
        while ($obTypeModule = $results->fetchObject(EntityTypeModules::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/type-module',[
                'id' => $obTypeModule->id,
                'type' => $obTypeModule->type,
                'module' => $module,
                'description' => $obTypeModule->description
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @return string
     */
    private static function getModulesItems(string $module): string
    {
        // levels
        $itens = '';

        $results = EntityModules::getModules('id > 0', 'id ASC');

        $i=1;
        //Renderiza o item
        while ($obModules = $results->fetchObject(EntityModules::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/module',[
                'id' => $obModules->id,
                'moduleName' => $obModules->module,
                'module' => $module,
                'label' => $obModules->label,
                'icon' => $obModules->icon,
                'pathModules' => $obModules->path_module,
                'type' => $obModules->type
            ]);
            $i++;
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
    private static function getAccessModules(int $levelId,string $module)
    {
        // access modules
        $itens = '';

        $results = EntityViewAccessModules::getViewAccessModules('level_id >=' . $levelId);

        //renderiza o item
        $i=1;
        while($obViewAccessModules = $results->fetchObject(EntityViewAccessModules::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/access-module', [
                'id' => $i,
                'modules' => $obViewAccessModules->module,
                'level' => $obViewAccessModules->level,
                'levelName' => $obViewAccessModules->description,
                'homePath' => $obViewAccessModules->home_path,
                'levelId' => $obViewAccessModules->level_id,
                'disabledEdit' => self::getAllowButton($levelId, 22),
                'disabledRemove' => self::getAllowButton($levelId, 23),
                'module' => $module
            ]);
            $i++;
        }

        // retornar itens
        return $itens;
    }

    /**
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getApikeyItems(int $levelId,string $module)
    {
        // access modules
        $itens = '';

        $results = EntityApiKey::getApiKey();

        //renderiza o item
        $i=1;
        while($obApikey = $results->fetchObject(EntityApiKey::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/apikey', [
                'id' => $obApikey->id,
                'username' => $obApikey->user_name,
                'email' => $obApikey->user_email,
                'apikey' => $obApikey->api_key,
                'apikeyName' => $obApikey->api_name,
                'apikeyPath' => $obApikey->api_path,
                'statusApikey' => ($obApikey->active == 1)? 'ACTIVE' : 'INACTIVE' ,
                'disabledEdit' => self::getAllowButton($levelId, 41),
                'disabledRemove' => self::getAllowButton($levelId, 42),
                'module' => $module
            ]);
            $i++;
        }

        // retornar itens
        return $itens;
    }

    /**
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getOrganizationItems(int $levelId,string $module)
    {
        // access modules
        $itens = '';

        $results = EntityOrganization::getOrganization();

        //renderiza o item
        $i=1;
        while($obOrganization = $results->fetchObject(EntityOrganization::class)){
            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/organization', [
                'id' => $obOrganization->id,
                'shortName' => $obOrganization->short_name,
                'fullName' => $obOrganization->full_name,
                'site' => $obOrganization->site,
                'development' => $obOrganization->development,
                'version' => $obOrganization->version,
                'disabledEdit' => self::getAllowButton($levelId, 120),
                'disabledRemove' => self::getAllowButton($levelId, 121),
                'module' => $module
            ]);
            $i++;
        }

        // retornar itens
        return $itens;
    }

    /**
     * @param int $levelId
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getWhasAppItems(int $levelId,string $module)
    {
        // access modules
        $itens = '';
        $results = EntityAccessTokenWhatsApp::getAccessTokenWhatsApp();

        //renderiza o item
        $i=1;
        while($obAccessTokenWhatsApp = $results->fetchObject(EntityAccessTokenWhatsApp::class)){

            $itens .= View::render('application/modules/advanced-settings/nav-tab/tab-pane/items/whatsapp', [
                'id' => $obAccessTokenWhatsApp->id,
                'businessPhoneNumberId' => $obAccessTokenWhatsApp->business_phone_number_id,
                'graphApiToken' => $obAccessTokenWhatsApp->graph_api_token,
                'expirationAt' => $obAccessTokenWhatsApp->expiration_at,
                'statusDescription' => $obAccessTokenWhatsApp->status_description,
                'disabledEdit' => self::getAllowButton($levelId, 125),
                'disabledRemove' => self::getAllowButton($levelId, 126),
                'module' => $module
            ]);
            $i++;
        }

        // retornar itens
        return $itens;
    }

    /**
     * @throws Exception
     */
    private static function checkOnlineUser(int $idUser):string
    {
        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $date = $date->setTimezone(new DateTimeZone('UTC'));
        $date->sub(new DateInterval('PT40S'));

        $obVistor = EntityVisitor::getUserOnline($idUser, $date->format('Y-m-d H:i:s'));

        if(!$obVistor instanceof EntityVisitor){
            return 'offline.png';
        }
        return 'online.png';
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
        $obTypeModulo = EntityTypeModules::getTypeModuleById(5);

        //Conteúdo de página de configuração
        $content = View::render('application/modules/advanced-settings/index',[
            'title' => $obOrganization->full_name,
            'description' => $obTypeModulo->description,
            'menuTab' => self::getNavTab($currentTab),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de configurações avançadas.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
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
            case 'reset-pwd':
                return Alert::getSuccess('Senha resetada com sucesso!');
                break;
            case 'resend-account-activated':
                return Alert::getSuccess('Link para ativação da conta reenviado com sucesso!');
                break;
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