<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\SessionLogin as EntitySessionLogin;
use App\Model\Entity\SendMessageWhatsApp as EntitySendMessageWhatsApp;
use App\Model\Entity\AcceptedInvite as EntityAcceptedInvite;
use App\Model\Entity\SendMessageWhatsAppAll as EntitySendMessageWhatsAppAll;
use App\Model\Entity\AcceptedInviteWhatsAppAll as EntityAcceptedInviteWhatsAppAll;
use App\Utils\Debug;
use App\Utils\Pagination;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class MonitoringWhatsApp extends Page
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
        $result = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 9','module_id ASC' );
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

            $links .= View::render('application/modules/monitoring-whatsapp/nav-tab/link', [
                'label' => $obAccessModules->label,
                'link' =>  $obAccessModules->path_module,
                'current' => $current
                //'current' => ($i === 1 && $currentModuleId === null) ? 'active' : (($currentTab === $obAccessModules->module) ? 'active' : null)
            ]);

            $tables = View::render('application/modules/monitoring-whatsapp/nav-tab/tab-pane/tables/'.$obAccessModules->module, [
                'itens' => self::getTables($obAccessModules->module, $levelId)
            ]);

            $tabsPane .= View::render('application/modules/monitoring-whatsapp/nav-tab/tab-pane/tab-pane', [
                'active' => ($currentModuleId === 129) ? 'active' : (($currentModuleId === 130) ? 'active' : null),
                'tabPaneId' => str_replace("#","",$obAccessModules->path_module),
                'titile' => $obAccessModules->label,
                'navTab' => $obAccessModules->module,
                'module' => $obAccessModules->module,
                'btnName' => 'Cadastrar',
                'table' => $tables
            ]);
            $i++;
        }

        // recupera módulos para o level
        $res = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 9','module_id ASC' );
        $obAccessModules = $res->fetchObject(EntityAccessModules::class);

        // Retorna a renderização do menu
        return View::render('application/modules/monitoring-whatsapp/nav-tab/box', [
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
    private static function getTables(string $module, int $levelId)
    {
        return match ($module) {
            'control-messages' => self::getControlMessagesItems($module),
            'control-invite' => self::getControlInviteItems($module),
            default => null,
        };
    }

    /**
     * @param string $module
     * @return string
     * @throws \DateInvalidOperationException
     */
    private static function getControlMessagesItems(string $module): string
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
        $results = EntitySendMessageWhatsAppAll::getSendMessageWhatsApp($where, 'created_at  DESC');
        // renderiza itens
        while ($obData = $results->fetchObject(EntitySendMessageWhatsAppAll::class)){
            $itens .= View::render('application/modules/monitoring-whatsapp/nav-tab/tab-pane/items/'.$module,[
                'id' => $obData->id,
                'module' => $module,
                'name' => $obData->complete_name,
                'phoneNumber' => $obData->phone_number,
                'messageId' => $obData->message_id,
                'messageStatus' => $obData->message_status,
                'typeMessage' => $obData->team_type,
                'payload' => $obData->payload//json_encode($obData->payload),
            ]);
        }

        return $itens;
    }

    /**
     * @param string $module
     * @return string
     * @throws Exception
     */
    private static function getControlInviteItems(string $module): string
    {
        // tipo módulos
        $itens = '';

        $results = EntityAcceptedInviteWhatsAppAll::getAcceptedInvite(null, 'created_at  DESC');
        // renderiza itens
        while ($obData = $results->fetchObject(EntityAcceptedInviteWhatsAppAll::class)){
            $schedulerDate = new DateTime($obData->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');
            $itens .= View::render('application/modules/monitoring-whatsapp/nav-tab/tab-pane/items/'.$module,[
                'id' => $obData->id,
                'schedulerId' => $obData->scheduler_id,
                'module' => $module,
                'name' => $obData->complete_name,
                'phoneNumber' => $obData->contato,
                'device' => ($obData->team_type == 'soundteam')? $obData->device: (($obData->team_type == 'worshipteam') ? 'LOUVOR' : 'RECEPÇÃO'),
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obData->day_long_description,
                'messageId' => $obData->message_id,
                'statusMessage' => $obData->status,
                'typeMessage' => $obData->team_type,
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
        $content = View::render('application/modules/monitoring-whatsapp/index',[
            'title' => $obOrganization->full_name,
            'description' => $obTypeModulo->description,
            'menuTab' => self::getNavTab($currentTab),
            'status' => self::getStatus($request)
        ]);

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Monitoramento WhatsApp',$content,'monitoring-whatsapp');
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
                return Alert::getError('Id mensagem não encontrado!');
                break;
            case 'rejected':
                return Alert::getWarning('Este registro não pode ser apagado, porque já está em uso!');
                break;
        }
    }
}