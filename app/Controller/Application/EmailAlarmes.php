<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\EmailAlarmes as EntityEmailAlarmes;
use App\Model\Entity\StatusEmail as EntityStatusEmail;
use App\Model\Entity\EmailVerified as EntityEmailVerified;
use App\Utils\Debug;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class EmailAlarmes extends Page
{
    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param string|null $selected
     * @return string
     * @returm string
     */
    private static function getStatusEmailItems(Request $request, string $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = null;
        $query = isset($selected)? 'id, status, description, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityStatusEmail::getStatusEmail($where, $order,null,null, $query);
        while ($obStatusEmail = $results->fetchObject(EntityStatusEmail::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obStatusEmail->id,
                'optionName' => $obStatusEmail->description
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
    private static function getEmailVerifiedItems(Request $request, string $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = null;
        $query = isset($selected)? 'id, status, description, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityEmailVerified::getEmailVerified($where, $order,null,null, $query);
        while ($obEmailVerified = $results->fetchObject(EntityEmailVerified::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obEmailVerified->id,
                'optionName' => $obEmailVerified->description
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
    public static function getNewEmailAlarmes(Request $request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/email-alert-configuration',[
            'title' => 'Cadastrar E-mail Alarmes',
            'breadcrumbItem' => 'Cadastrar E-mail Alarmes',
            'name' => null,
            'email' => null,
            'optionsStatusEmail' => self::getStatusEmailItems($request,2),
            'optionsStatusVerified' => self::getEmailVerifiedItems($request,2),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de email alarmes.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | E-mail Alarmes',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewEmailAlarmes(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $statusEmail = $postVars['statusEmail'] ?? '';
        $statusVerified = $postVars['statusVerified'] ?? '';

        // Nova instancia
        $obEmailAlarmes = new EntityEmailAlarmes();
        $obEmailAlarmes->name = $name;
        $obEmailAlarmes->email = $email;
        $obEmailAlarmes->status = $statusEmail;
        $obEmailAlarmes->email_verified = $statusVerified;
        $obEmailAlarmes->cadastrar();



        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration/'.$obEmailAlarmes->id.'/edit?status=created');
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
                return Alert::getSuccess('E-mail Alarmes criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('E-mail Alarmes atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('E-mail Alarmes excluído com sucesso!');
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
    public static function getEditEmailAlertas(Request $request,int $id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obEmailAlertas = EntityEmailAlarmes::getEmailAlarmesById($id);

        // Valida instância
        if(!$obEmailAlertas instanceof EntityEmailAlarmes){
            $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/email-alert-configuration',[
            'title' => 'Editar E-mail Alarmes',
            'breadcrumbItem' => 'Editar E-mail Alarmes',
            'name' => $obEmailAlertas->name,
            'email' =>$obEmailAlertas->email,
            'optionsStatusEmail' => self::getStatusEmailItems($request,$obEmailAlertas->status_id),
            'optionsStatusVerified' => self::getEmailVerifiedItems($request,$obEmailAlertas->status_verified_id),
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de email alarmes.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | E-mail Alertas',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setEditEmailAlarmes(Request $request, int $id)
    {
        // Obtém o depoimento do banco de dados
        $obEmailAlertas = EntityEmailAlarmes::getEmailAlarmesById($id);

        // Valida instância
        if(!$obEmailAlertas instanceof EntityEmailAlarmes){
            $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $statusEmail = $postVars['statusEmail'] ?? '';
        $statusVerified = $postVars['statusVerified'] ?? '';

        // Nova instancia
        $obEmailAlarmes = new EntityEmailAlarmes();
        $obEmailAlarmes->id = $id;
        $obEmailAlarmes->name = $name;
        $obEmailAlarmes->email = $email;
        $obEmailAlarmes->status = $statusEmail;
        $obEmailAlarmes->email_verified = $statusVerified;
        $obEmailAlertas->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration/'.$obEmailAlarmes->id.'/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteEmailAlert($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obEmailAlertas = EntityEmailAlarmes::getEmailAlarmesById($id);

        // Valida instância
        if(!$obEmailAlertas instanceof EntityEmailAlarmes){
            $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/email-alert-configuration',[
            'title' => 'Excluir E-mail Alerta',
            'breadcrumbItem' => 'Excluir E-mail Alerta',
            'nome' => $obEmailAlertas->name,
            'email' => $obEmailAlertas->email
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de email alarmes.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | E-mail Alertas',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setDeleteEmailAlert(Request $request, int $id)
    {
        // Obtém o depoimento do banco de dados
        $obEmailAlertas = EntityEmailAlarmes::getEmailAlarmesById($id);

        // Valida instância
        if(!$obEmailAlertas instanceof EntityEmailAlarmes){
            $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration?status=failed');
        }

        $obEmailAlertas = new EntityEmailAlarmes();
        $obEmailAlertas->id = $id;
        $obEmailAlertas->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/email-alert-configuration?status=deleted');

    }
}