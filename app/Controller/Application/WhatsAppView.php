<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SendMessageWhatsAppAll as EntitySendMessageWhatsApp;
use App\Model\Entity\AcceptedInviteWhatsAppAll as EntityAcceptedInvite;
use App\Utils\Debug;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class WhatsAppView extends Page
{

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
        return match ($queryParams['status']) {
            'updated-pwd', 'updated' => Alert::getSuccess('Senha atualizada com sucesso!'),
            'failed' => Alert::getError('Ocorreu um erro ao buscar id da mensagem!'),
            default => null,
        };
    }

    /**
     * @param Request $request
     * @param string $module
     * @param $id
     * @return string
     */
    private static function getControlMessagesItems(Request $request,string $module, $id): string
    {
        // recupera team_type
        $getParams = $request->getQueryParams();
        $teamType = $getParams['team'];

        // recupera messageId
        $obDataMessage = EntityAcceptedInvite::getSendMessageByIdByTeamType($id, $teamType);

        if(!$obDataMessage instanceof EntityAcceptedInvite) $request->getRouter()->redirect('/application/monitoring-whatsapp/'.$module.'?status=failed');;

        $messageId = $obDataMessage->message_id;

        // tipo módulos
        $itens = '';
        $where = ' message_id = "'. $messageId . '"';
        $results = EntitySendMessageWhatsApp::getSendMessageWhatsApp($where, 'created_at  DESC');
        // renderiza itens
        while ($obData = $results->fetchObject(EntitySendMessageWhatsApp::class)){
            $itens .= View::render('application/modules/monitoring-whatsapp/nav-tab/tab-pane/items/control-messages',[
                'id' => $obData->id,
                'module' => $module,
                'typeMessage' => $obData->team_type,
                'name' => $obData->complete_name,
                'phoneNumber' => $obData->phone_number,
                'messageId' => $obData->message_id,
                'messageStatus' => $obData->message_status,
                'payload' => $obData->payload//json_encode($obData->payload),
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @param $navTab
     * @param $id
     * @return string
     * @throws Exception
     */
    public static function getSearchView(Request $request, $navTab,$id): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        $title = 'Tracking mensagens';
        $mainTitle = 'Tracking mensagens - WhatsApp';

        //Conteúdo da home
        $content = View::render('application/modules/monitoring-whatsapp/control-invite/index',[
            'mainTitle' => $mainTitle,
            'title'=>$title,
            'items' => self::getControlMessagesItems($request,$navTab,$id),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página home.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Monitoramento WhatsApp',$content,'monitoring-whatsapp');
    }
}