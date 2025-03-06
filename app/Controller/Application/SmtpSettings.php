<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SettingsSmtp as EntitySettingsSmtp;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Model\Entity\StatusSmtp as EntityStatusSmtp;
use App\Utils\Debug;
use App\Utils\GeneratePassword;
use App\Utils\Pagination;
use App\Utils\PasswordEncryptor;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class SmtpSettings extends Page
{
    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @returm string
     */
    private static function getApikeyItems($request, &$obPagination, $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = 'api_path = "/email/active-account"';
        $query = isset($selected)? 'id, user_name, api_key, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityApikey::getApiKey($where, $order,null,null, $query);
        while ($obApikey = $results->fetchObject(EntityApikey::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obApikey->id,
                'optionName' => $obApikey->user_name . ' - ' . $obApikey->api_key
            ]);
        }

        // retorna os options levels
        return $options;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @returm string
     */
    private static function getStatusSmtpItems($request, &$obPagination, $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = '';
        $query = isset($selected)? 'id, description, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityStatusSmtp::getStatusSmtp($where, $order,null,null, $query);
        while ($obStatusStmp = $results->fetchObject(EntityStatusSmtp::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obStatusStmp->id,
                'optionName' => $obStatusStmp->description
            ]);
        }

        // retorna os options levels
        return $options;

    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewSmtpSettings($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/smtp-settings',[
            'title' => 'Cadastrar Smtp',
            'breadcrumbItem' => 'Cadastrar Smtp',
            'optionsApikey' => self::getApikeyItems($request, $obPagination),
            'optionsStatusSmtp' => self::getStatusSmtpItems($request, $obPagination),
            'host' => null,
            'port' => null,
            'email' => null,
            'name' => null,
            'password' => null,
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de smtp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Smtp Settings',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewSmtpSettings(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $host = $postVars['host'] ?? '';
        $port = $postVars['port'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';
        $name = $postVars['name'] ?? '';
        $apikeyId = $postVars['apikey'] ?? '';
        $statusSmtp = $postVars['statusSmtp'] ?? '';

        // Nova instancia de usuários
        $obSettingsSmtp = new EntitySettingsSmtp();
        $obSettingsSmtp->host = $host;
        $obSettingsSmtp->port = $port;
        $obSettingsSmtp->username = $email;
        $obSettingsSmtp->password = GeneratePassword::encryptPassword($password);
        $obSettingsSmtp->from_name = $name;
        $obSettingsSmtp->id_apikey = $apikeyId;
        $obSettingsSmtp->status_id = $statusSmtp;
        $obSettingsSmtp->cadastrar();


        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/smtp-settings/'.$obSettingsSmtp->id.'/edit?status=created');
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request)
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Smtp criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Smtp atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Smtp excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail ou login digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Você não pode excluir seu próprio usuário!');
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
    public static function getEditSmtpSettings($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obSettingsSmtp = EntitySettingsSmtp::getSettingsSmtpById($id);

        // Valida instância
        if(!$obSettingsSmtp instanceof EntitySettingsSmtp){
            $request->getRouter()->redirect('/application/advanced-settings/smtp-settings?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/smtp-settings',[
            'title' => 'Editar Smtp',
            'breadcrumbItem' => 'Editar Smtp',
            'optionsApikey' => self::getApikeyItems($request, $obPagination),
            'optionsStatusSmtp' => self::getStatusSmtpItems($request,$obPagination,$obSettingsSmtp->status_id),
            'host' => $obSettingsSmtp->host,
            'port' => $obSettingsSmtp->port,
            'email' => $obSettingsSmtp->username,
            'name' => $obSettingsSmtp->from_name,
            'password' => PasswordEncryptor::decryptPassword($obSettingsSmtp->password),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);


        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de smtp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Smtp Settings',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setEditSmtpSettings($request,$id)
    {
        // Obtém dados do banco de dados
        $obSettingsSmtp = EntitySettingsSmtp::getSettingsSmtpById($id);

        // Valida instância
        if(!$obSettingsSmtp instanceof EntitySettingsSmtp){
            $request->getRouter()->redirect('/application/advanced-settings/smtp-settings?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $host = $postVars['host'] ?? '';
        $port = $postVars['port'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';
        $name = $postVars['name'] ?? '';
        $apikeyId = $postVars['apikey'] ?? '';
        $statusSmtp = $postVars['statusSmtp'] ?? '';

        // Nova instancia de usuários
        $obSettingsSmtp = new EntitySettingsSmtp();
        $obSettingsSmtp->id = $id;
        $obSettingsSmtp->host = $host;
        $obSettingsSmtp->port = $port;
        $obSettingsSmtp->username = $email;
        $obSettingsSmtp->password = GeneratePassword::encryptPassword($password);
        $obSettingsSmtp->from_name = $name;
        $obSettingsSmtp->id_apikey = $apikeyId;
        $obSettingsSmtp->status_id = $statusSmtp;
        $obSettingsSmtp->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/smtp-settings/'.$obSettingsSmtp->id.'/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteSmtpSettings($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém dados do banco de dados
        $obSettingsSmtp = EntitySettingsSmtp::getSettingsSmtpById($id);

        // Valida instância
        if(!$obSettingsSmtp instanceof EntitySettingsSmtp){
            $request->getRouter()->redirect('/application/advanced-settings/smtp-settings?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/smtp-settings',[
            'title' => 'Excluir Smtp',
            'breadcrumbItem' => 'Excluir Smtp',
            'nome' => $obSettingsSmtp->from_name,
            'email' => $obSettingsSmtp->username
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de smtp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Smtp Settings',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setDeleteSmtpSettings($request,$id)
    {
        // Obtém dados do banco de dados
        $obSettingsSmtp = EntitySettingsSmtp::getSettingsSmtpById($id);

        // Valida instância
        if(!$obSettingsSmtp instanceof EntitySettingsSmtp){
            $request->getRouter()->redirect('/application/advanced-settings/smtp-settings?status=failed');
        }

        $obSettingsSmtp = new EntitySettingsSmtp();
        $obSettingsSmtp->id = $id;
        $obSettingsSmtp->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/smtp-settings?status=deleted');

    }
}