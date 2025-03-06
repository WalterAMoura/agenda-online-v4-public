<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\StatusApikey as EntityStatusApikey;
use App\Model\Entity\StatusToken as EntityStatusToken;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Model\Entity\AccessTokenWhatsApp as EntityAccessTokenWhatsApp;
use App\Utils\ApiKeyGenerator;
use App\Utils\Debug;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class AccessTokenWhatsApp extends Page
{

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param string|null $selected
     * @return string
     * @returm string
     */
    private static function getUsersItems(Request $request, string $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = null;
        $query = isset($selected)? 'id, name, login, email, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityUser::getUsers($where, $order,null,null, $query);
        while ($obUser = $results->fetchObject(EntityUser::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obUser->id,
                'optionName' => $obUser->name
            ]);
        }

        // retorna os options levels
        return $options;
    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param string|null $selected
     * @return string
     * @returm string
     */
    private static function getApikeyStatusItems(Request $request, string $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = null;
        $query = isset($selected)? 'id, description, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityStatusApikey::getStatusApikey($where, $order,null,null, $query);
        while ($obStatusApikey = $results->fetchObject(EntityStatusApikey::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obStatusApikey->id,
                'optionName' => $obStatusApikey->description
            ]);
        }

        // retorna os options levels
        return $options;
    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getStatusTokenItems(Request $request, string $selected = null)
    {

        // recupera status tokens
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = null;
        $query = isset($selected)? 'id, description, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityStatusToken::getStatusToken($where, $order,null,null, $query);
        while ($obStatusToken = $results->fetchObject(EntityStatusToken::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obStatusToken->id,
                'optionName' => $obStatusToken->description
            ]);
        }

        // retorna os options levels
        return $options;
    }

    /**
     * Método responsável por gerar a apikey
     * @return mixed
     */
    private static function generateApikey()
    {
        $apikey = new ApiKeyGenerator();
        return $apikey->getApiKey();
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewAccessTokenWhatsApp(Request $request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/whatsapp',[
            'title' => 'Cadastrar Token de Acesso',
            'breadcrumbItem' => 'Cadastrar Token de Acesso',
            'businessPhoneNumberId' => null,
            'graphApiToken' => null,
            'optionsStatusTokenWhatsApp' => self::getStatusTokenItems($request),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de token acesso WhatsApp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | oken WhatsApp',$content,'advanced-settings');
    }

    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    private static function setDisableOldAccessTokenWhatsApp(Request $request)
    {
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppByStatusId(3);
        if($obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            $id = $obAccessTokenWhatsApp->id;
            $obAccessTokenWhatsApp = new EntityAccessTokenWhatsApp();
            $obAccessTokenWhatsApp->id = $id;
            $obAccessTokenWhatsApp->status_id = 4;
            $obAccessTokenWhatsApp->updateStatusToken();
        }
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewAccessTokenWhatsApp(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();
        //Debug::debug($postVars);
        $businessPhoneNumberId = $postVars['businessPhoneNumberId']?? '';
        $graphApiToken = $postVars['graphApiToken']?? '';
        $statusTokenWhatsApp = $postVars['statusTokenWhatsApp']?? '';
        $expirationDate = new DateTime('now');
        $expirationDate->modify('+900 minutes');
        $expirationDate->setTimezone(new DateTimeZone('UTC'));

        // verifica se existi um token ativo e muda o status para inativo
        self::setDisableOldAccessTokenWhatsApp($request);

        // Nova instancia
        $obAccessTokenWhatsApp = new EntityAccessTokenWhatsApp();
        $obAccessTokenWhatsApp->business_phone_number_id = $businessPhoneNumberId;
        $obAccessTokenWhatsApp->graph_api_token = $graphApiToken;
        $obAccessTokenWhatsApp->status_id = $statusTokenWhatsApp;
        $obAccessTokenWhatsApp->expiration_at = $expirationDate->format('Y-m-d H:i:s');
        $obAccessTokenWhatsApp->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/whatsapp/'.$obAccessTokenWhatsApp->id.'/edit?status=created');

        return [
            'success' => true
        ];
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
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
                return Alert::getSuccess('Token WhatsApp criada com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Token WhatsApp atualizada com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Token WhatsApp excluída com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Token WhatsApp digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Você não pode excluir seu próprio Token WhatsApp!');
                break;
        }
    }


    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditAccessTokenWhatsApp(Request $request,int $id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém dados do banco de dados
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppById($id);

        // Valida instância
        if(!$obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            $request->getRouter()->redirect('/application/advanced-settings/whatsapp?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/whatsapp',[
            'title' => 'Editar Token de acesso',
            'breadcrumbItem' => 'Editar Token de acesso',
            'businessPhoneNumberId' => $obAccessTokenWhatsApp->business_phone_number_id,
            'graphApiToken' => $obAccessTokenWhatsApp->graph_api_token,
            'optionsStatusTokenWhatsApp' => self::getStatusTokenItems($request,$obAccessTokenWhatsApp->status_id),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de token acesso WhatsApp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Token WhatsApp',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditTokenWhatsApp(Request $request, int $id)
    {
        // Obtém dados do banco de dados
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppById($id);

        // Valida instância
        if(!$obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            $request->getRouter()->redirect('/application/advanced-settings/whatsapp?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $businessPhoneNumberId = $postVars['businessPhoneNumberId']?? '';
        $graphApiToken = $postVars['graphApiToken']?? '';
        $statusTokenWhatsApp = $postVars['statusTokenWhatsApp']?? '';
        $expirationDate = new DateTime('now');
        $expirationDate->modify('+900 minutes');
        $expirationDate->setTimezone(new DateTimeZone('UTC'));

        // Nova instancia
        $obAccessTokenWhatsApp = new EntityAccessTokenWhatsApp();
        $obAccessTokenWhatsApp->id = $id;
        $obAccessTokenWhatsApp->business_phone_number_id = $businessPhoneNumberId;
        $obAccessTokenWhatsApp->graph_api_token = $graphApiToken;
        $obAccessTokenWhatsApp->status_id = $statusTokenWhatsApp;
        $obAccessTokenWhatsApp->expiration_at = $expirationDate->format('Y-m-d H:i:s');
        $obAccessTokenWhatsApp->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/whatsapp/'.$obAccessTokenWhatsApp->id.'/edit?status=updated');

        return [
            'success' => true
        ];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteTokenWhatsApp($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém dados do banco de dados
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppById($id);

        // Valida instância
        if(!$obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            $request->getRouter()->redirect('/application/advanced-settings/whatsapp?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/whatsapp',[
            'title' => 'Excluir Token WhatsApp',
            'breadcrumbItem' => 'Excluir Token WhatsApp',
            'phoneId' => $obAccessTokenWhatsApp->business_phone_number_id,
            'status' => $obAccessTokenWhatsApp->status_id,
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de whatsapp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Token WhatsApp',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setDeleteTokenWhatsApp(Request $request, int $id)
    {
        // Obtém dados do banco de dados
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppById($id);

        // Valida instância
        if(!$obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            $request->getRouter()->redirect('/application/advanced-settings/whatsapp?status=failed');
        }

        $obAccessTokenWhatsApp = new EntityAccessTokenWhatsApp();
        $obAccessTokenWhatsApp->id = $id;
        $obAccessTokenWhatsApp->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/whatsapp?status=deleted');

        return [
            'success' => true
        ];

    }
}