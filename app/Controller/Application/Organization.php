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


class Organization extends Page
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
    public static function getNewOrganization(Request $request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/organization',[
            'title' => 'Cadastrar Organização',
            'breadcrumbItem' => 'Cadastrar Organização',
            'shortName' => null,
            'fullName' => null,
            'site' => null,
            'description' => null,
            'development' => null,
            'version' => null,
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de organização.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Organização',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewOrganization(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $shortName = $postVars['shortName'] ?? '';
        $fullName = $postVars['fullName'] ?? '';
        $site = $postVars['site'] ?? '';
        $description = $postVars['description'] ?? '';
        $development = $postVars['development'] ?? '';
        $version = $postVars['version'] ?? '';

        // Nova instancia
        $obOrganization = new EntityOrganization();
        $obOrganization->short_name = $shortName;
        $obOrganization->full_name = $fullName;
        $obOrganization->site = $site;
        $obOrganization->description = $description;
        $obOrganization->development = $development;
        $obOrganization->version = $version;
        $obOrganization->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/organization/'.$obOrganization->id.'/edit?status=created');

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
                return Alert::getSuccess('Organization criada com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Organization atualizada com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Organization excluída com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Organization digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Você não pode excluir seu próprio organization!');
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
    public static function getEditOrganization(Request $request,int $id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obOrganizationI = EntityOrganization::getOrganizationById($id);

        //Debug::debug($obApikey);

        // Valida instância
        if(!$obOrganizationI instanceof EntityOrganization){
            $request->getRouter()->redirect('/application/advanced-settings/organization?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/organization',[
            'title' => 'Editar Organização',
            'breadcrumbItem' => 'Editar Organização',
            'shortName' => $obOrganizationI->short_name,
            'fullName' => $obOrganizationI->full_name,
            'site' => $obOrganizationI->site,
            'description' => $obOrganizationI->description,
            'development' => $obOrganizationI->development,
            'version' => $obOrganizationI->version,
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de organization.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Organização',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditOrganization(Request $request, int $id)
    {
        // Obtém o depoimento do banco de dados
        $obOrganization = EntityOrganization::getOrganizationById($id);

        // Valida instância
        if(!$obOrganization instanceof EntityOrganization){
            $request->getRouter()->redirect('/application/advanced-settings/organization?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $shortName = $postVars['shortName'] ?? '';
        $fullName = $postVars['fullName'] ?? '';
        $site = $postVars['site'] ?? '';
        $description = $postVars['description'] ?? '';
        $development = $postVars['development'] ?? '';
        $version = $postVars['version'] ?? '';

        // Nova instancia
        $obOrganization = new EntityOrganization();
        $obOrganization->id =  $id;
        $obOrganization->short_name = $shortName;
        $obOrganization->full_name = $fullName;
        $obOrganization->site = $site;
        $obOrganization->description = $description;
        $obOrganization->development = $development;
        $obOrganization->version = $version;
        $obOrganization->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/organization/'.$obOrganization->id.'/edit?status=updated');

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
    public static function getDeleteOrganization($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obOrganizationI = EntityOrganization::getOrganizationById($id);

        // Valida instância
        if(!$obOrganizationI instanceof EntityOrganization){
            $request->getRouter()->redirect('/application/advanced-settings/organization?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/organization',[
            'title' => 'Excluir Organization',
            'breadcrumbItem' => 'Excluir Organization',
            'shortName' => $obOrganizationI->short_name,
            'site' => $obOrganizationI->site,
            'version' => $obOrganizationI->version
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de organization.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Organização',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setDeleteOrganization(Request $request, int $id)
    {
        // Obtém o depoimento do banco de dados
        $obOrganization = EntityOrganization::getOrganizationById($id);

        // Valida instância
        if(!$obOrganization instanceof EntityOrganization){
            $request->getRouter()->redirect('/application/advanced-settings/organization?status=failed');
        }

        $obOrganization = new EntityOrganization();
        $obOrganization->id = $id;
        $obOrganization->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/organization?status=deleted');

        return [
            'success' => true
        ];

    }
}