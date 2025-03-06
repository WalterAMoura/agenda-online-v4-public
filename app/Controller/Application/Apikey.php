<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\StatusApikey as EntityStatusApikey;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Utils\ApiKeyGenerator;
use App\Utils\Debug;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class Apikey extends Page
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
    public static function getNewApikey(Request $request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/apikey',[
            'title' => 'Cadastrar Apikey',
            'breadcrumbItem' => 'Cadastrar Apikey',
            'optionsUsers' => self::getUsersItems($request),
            'apikey' => self::generateApikey(),
            'apikeyName' => null,
            'apikeyDescription' => null,
            'apikeyPath' => null,
            'optionsStatusApikey' => self::getApikeyStatusItems($request),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de apikey.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Apikey',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewApikey(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $userId = $postVars['userId'] ?? '';
        $apikey = $postVars['apikey'] ?? '';
        $apikeyName = $postVars['apikeyName'] ?? '';
        $apikeyDescription = $postVars['apikeyDescription'] ?? '';
        $apikeyPath = $postVars['apikeyPath'] ?? '';
        $statusApikey = $postVars['statusApikey'] ?? '';

        // Nova instancia
        $obApikey = new EntityApikey();
        $obApikey->user_id = $userId;
        $obApikey->api_key = $apikey;
        $obApikey->api_name = $apikeyName;
        $obApikey->api_description = $apikeyDescription;
        $obApikey->api_path = $apikeyPath;
        $obApikey->status_id = $statusApikey;
        $obApikey->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/apikey/'.$obApikey->id.'/edit?status=created');

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
                return Alert::getSuccess('Apikey criada com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Apikey atualizada com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Apikey excluída com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Apikey digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Você não pode excluir seu próprio apikey!');
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
    public static function getEditApikey(Request $request,int $id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obApikey = EntityApikey::getApikeyById($id);

        //Debug::debug($obApikey);

        // Valida instância
        if(!$obApikey instanceof EntityApikey){
            $request->getRouter()->redirect('/application/advanced-settings/apikey?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/apikey',[
            'title' => 'Editar Apikey',
            'breadcrumbItem' => 'Editar Apikey',
            'optionsUsers' => self::getUsersItems($request, $obApikey->user_id),
            'apikey' => $obApikey->api_key,
            'apikeyName' => $obApikey->api_name,
            'apikeyDescription' => $obApikey->api_description,
            'apikeyPath' => $obApikey->api_path,
            'optionsStatusApikey' => self::getApikeyStatusItems($request, $obApikey->status_id),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de apikey.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Apikey',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditApikey(Request $request, int $id)
    {
        // Obtém o depoimento do banco de dados
        $obApikey = EntityApikey::getApikeyById($id);

        // Valida instância
        if(!$obApikey instanceof EntityApikey){
            $request->getRouter()->redirect('/application/advanced-settings/apikey?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $userId = $postVars['userId'] ?? '';
        $apikey = $postVars['apikey'] ?? '';
        $apikeyName = $postVars['apikeyName'] ?? '';
        $apikeyDescription = $postVars['apikeyDescription'] ?? '';
        $apikeyPath = $postVars['apikeyPath'] ?? '';
        $statusApikey = $postVars['statusApikey'] ?? '';

        // Nova instancia
        $obApikey = new EntityApikey();
        $obApikey->id = $id;
        $obApikey->user_id = $userId;
        $obApikey->api_key = $apikey;
        $obApikey->api_name = $apikeyName;
        $obApikey->api_description = $apikeyDescription;
        $obApikey->api_path = $apikeyPath;
        $obApikey->status_id = $statusApikey;
        $obApikey->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/apikey/'.$obApikey->id.'/edit?status=updated');

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
    public static function getDeleteApikey($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obApikey = EntityApikey::getApikeyById($id);

        // Valida instância
        if(!$obApikey instanceof EntityApikey){
            $request->getRouter()->redirect('/application/advanced-settings/apikey?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/apikey',[
            'title' => 'Excluir Apikey',
            'breadcrumbItem' => 'Excluir Apikey',
            'apikey' => $obApikey->api_key,
            'apikeyName' => $obApikey->api_name,
            'apikeyPath' => $obApikey->api_path
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de apikey.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Apikey',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setDeleteApikey(Request $request, int $id)
    {
        // Obtém o depoimento do banco de dados
        $obApikey = EntityApikey::getApikeyById($id);

        // Valida instância
        if(!$obApikey instanceof EntityApikey){
            $request->getRouter()->redirect('/application/advanced-settings/apikey?status=failed');
        }

        $obApikey = new EntityApikey();
        $obApikey->id = $id;
        $obApikey->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/apikey?status=deleted');

        return [
            'success' => true
        ];

    }
}