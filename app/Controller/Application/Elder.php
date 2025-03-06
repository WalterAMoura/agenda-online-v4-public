<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\User as EntityUser;
use App\Utils\View;
use App\Model\Entity\Elder as EntityElder;
use App\Utils\General;
use Exception;

class Elder extends Page
{
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
                return Alert::getSuccess('Ancião criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Ancião atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Ancião excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Ancião digitado já exite!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar ancião!');
                break;
            case 'rejected':
                return Alert::getWarning('Ancião não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por lista os usuários
     * @param string|null $selected
     * @return string
     */
    private static function getUsers(string $selected = null): string
    {
        // carregar dias da semana
        $options = '';

        $order = 'id ASC';
        $where = 'id_status = 1';
        $results = EntityUser::getUsers($where, $order);
        while ($obUser = $results->fetchObject(EntityUser::class)){
            $options .= View::render('application/modules/manager-sound-team/forms/select',[
                'optionValue' => $obUser->id,
                'optionName' => $obUser->name,
                'selected' => ($obUser->name === $selected)? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewElder(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/elder',[
            'title' => 'Cadastrar Ancião',
            'breadcrumbItem' => 'Cadastrar Ancião',
            'completeName' => null,
            'name' => null,
            'telefone' => null,
            'linkedUser' => self::getUsers(),
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página do ancionato.');
        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewElder(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $completeName = $postVars['completeName'] ?? '';
        $telefone = $postVars['telefone'] ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        $linkedUser = $postVars['linkedUser'] ?? '';

        if(General::isNullOrEmpty($postVars['name']) && General::isNullOrEmpty($postVars['telefone']) && General::isNullOrEmpty($postVars['completeName'])){
            $request->getRouter()->redirect('/application/config-event/elder/new?status=failed');
        }

        // valida se o ancião já existe
        $obElders = EntityElder::getElderByName($name);
        if($obElders instanceof EntityElder){
            $request->getRouter()->redirect('/application/config-event/elder/new?status=duplicated');
        }

        // Nova instancia de departamento
        $obElders = new EntityElder();
        $obElders->complete_name = $completeName;
        $obElders->name = $name;
        $obElders->contato = $telefone;
        $obElders->user_id = $linkedUser;
        $obElders->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder/'.$obElders->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditElder(Request $request, int $id): string
    {
        // Obtém o elder do banco de dados
        $obElder = EntityElder::getElderById($id);

        // Valida instância
        if(!$obElder instanceof EntityElder){
            $request->getRouter()->redirect('/application/config-event');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/elder',[
            'title' => 'Editar Ancião',
            'breadcrumbItem' => 'Editar Ancião',
            'completeName' =>$obElder->complete_name,
            'name' => $obElder->name,
            'telefone' => $obElder->contato,
            'linkedUser' => self::getUsers($obElder->linked_user_name),
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página do ancionato.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' Configurações',$content,'config-event');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditElder(Request $request, int $id): array
    {

        // Obtém o elder do banco de dados
        $obElder = EntityElder::getElderById($id);

        // Valida instância
        if(!$obElder instanceof EntityElder){
            $request->getRouter()->redirect('/application/config-event/elder?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $completeName = $postVars['completeName'] ?? '';
        $telefone = $postVars['telefone'] ?? '';
        $telefone = preg_replace("/[^0-9]/", "", $telefone);
        $linkedUser = $postVars['linkedUser'] ?? '';

        if(General::isNullOrEmpty($postVars['name']) && General::isNullOrEmpty($postVars['telefone']) && General::isNullOrEmpty($postVars['completeName'])){
            $request->getRouter()->redirect('/application/config-event/department/new?status=failed');
        }

        // valida departamento já existe
        $obElder = EntityElder::getElderByName($name);
        if($obElder instanceof EntityElder && $obElder->id != $id){
            $request->getRouter()->redirect('/application/config-event/department/new?status=duplicated');
        }

        // Nova instancia de departamento
        $obElder = new EntityElder();
        $obElder->id = $id;
        $obElder->complete_name = $completeName;
        $obElder->name = $name;
        $obElder->contato = $telefone;
        $obElder->user_id = $linkedUser;
        $obElder->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder/'.$obElder->id.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um departamento
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteElder(Request $request, int $id): string
    {
        // Obtém o elder do banco de dados
        $obElder = EntityElder::getElderById($id);

        // Valida instância
        if(!$obElder instanceof EntityElder){
            $request->getRouter()->redirect('/application/config-event/elder?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/delete/elder',[
            'title' => 'Excluir Ancião',
            'breadcrumbItem' => 'Excluir Ancião',
            'name' => $obElder->complete_name,
            'telefone' => $obElder->contato,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página do ancionato.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por excluir de um departamento
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setDeleteElder(Request $request, int $id): array
    {
        // Obtém o elder do banco de dados
        $obElder = EntityElder::getElderById($id);

        // Valida instância
        if(!$obElder instanceof EntityElder){
            $request->getRouter()->redirect('/application/config-event/elder?status=failed');
        }

        // Obtém o depoimento do banco de dados
        $obElder = EntityElder::getElderById($id);

        // Valida instância
        if($obElder instanceof EntityElder){
            $request->getRouter()->redirect('/application/config-event/elder/'.$id.'/delete?status=rejected');
        }

        // Excluir o usuário
        $obElder->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder?status=deleted');

        return [ "success" => true];

    }
}