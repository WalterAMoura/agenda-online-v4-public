<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\ControlToken;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\SessionLogin as EntitySessionLogin;
use App\Model\Entity\Logs as EntityLogs;
use App\Controller\Log as ControllerLog;
use App\Utils\Debug;
use App\Utils\Pagination;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class Monitoring extends Page
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
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 8','module_id ASC' );
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

            $links .= View::render('application/modules/monitoring/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/monitoring/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId)
            ]);

            $tabsPane .= View::render('application/modules/monitoring/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 101) ? 'active' : (($currentModuleId === 102) ? 'active' : null),
                'tabPaneId' => str_replace("#","",$obAccessModules->path_module),
                'titile' => $obAccessModules->label,
                'navTab' => $obAccessModules->module,
                'module' => $obAccessModules->module,
                //'disabledCreate' => self::getDisabledCreated($obAccessModules->module,$levelId),
                'btnName' => 'Cadastrar',
                'table' => $tables
            ]);
            $i++;
        }

        // recupera módulos para o level
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 8','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/monitoring/nav-tab/box', [
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
            'session-login' => self::getSessionLoginItems($module),
            'log' => self::getLogsItems($module),
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
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getSessionLoginItems(string $module): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // levels
        $itens = '';

        $results = EntitySessionLogin::getSessionLogin(null, 'id DESC');

        //Renderiza o item
        while ($obSessionLogin = $results->fetchObject(EntitySessionLogin::class)){
            $itens .= View::render('application/modules/monitoring/nav-tab/tab-pane/items/'.$module,[
                'startDate' => $obSessionLogin->data_inicio,
                'token' => $obSessionLogin->token,
                'login' => $obSessionLogin->login_user,
                'username' => $obSessionLogin->name_user,
                'device' => $obSessionLogin->user_agent,
                'endTime' => $obSessionLogin->tempo_final,
                'endDate' => $obSessionLogin->data_fim,
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
    private static function getLogsItems(string $module): string
    {
        // Criar um objeto DateTime com a data e hora atual
        $currentDate = new DateTime();

        // Subtrair 30 dias
        $currentDate->sub(new DateInterval('P30D'));

        // Exibir a data e hora resultante
        $currentDate = $currentDate->format('Y-m-d H:i:s');

        // tipo módulos
        $itens = '';
        $where = ' created_at >= "'. $currentDate . '"';
        $results = EntityLogs::getLogs($where, 'created_at  DESC');
        // renderiza itens
        while ($obLogs = $results->fetchObject(EntityLogs::class)){
            $itens .= View::render('application/modules/monitoring/nav-tab/tab-pane/items/'.$module,[
                'id' => $obLogs->id,
                'createdAt' => $obLogs->created_at,
                'module' => $module,
                'userId' => $obLogs->id_user,
                'username' => $obLogs->name,
                'userLogin' => $obLogs->login,
                'sessionToken' => $obLogs->token,
                'application' => $obLogs->application,
                'data' => $obLogs->data
            ]);
        }

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
        $obTypeModulo = EntityTypeModules::getTypeModuleById(5);

        //Conteúdo de página de configuração
        $content = View::render('application/modules/monitoring/index',[
            'title' => $obOrganization->full_name,
            'description' => $obTypeModulo->description,
            'menuTab' => self::getNavTab($currentTab),
            'status' => self::getStatus($request)
        ]);

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Monitoramento',$content,'monitoring');
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