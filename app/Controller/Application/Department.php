<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\View;
use App\Model\Entity\Event as EntityEvent;
use App\Model\Entity\Departaments as EntityDepartment;
use App\Utils\General;
use Exception;

class Department extends Page
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
                return Alert::getSuccess('Departamento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Departamento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Departamento excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Departamento digitado já exite!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar departamento!');
                break;
            case 'rejected':
                return Alert::getWarning('Departamento não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewDepartment(Request $request): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/departments',[
            'title' => 'Cadastrar Departamento',
            'breadcrumbItem' => 'Cadastrar Departamento',
            'department' => null,
            'departmentDirector' => null,
            'phoneNumber' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de departamentos.');

        // Retorna a pagina completa
        return parent::getPanel( $obOrganization->full_name .' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewDepartment(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $department = $postVars['department'] ?? '';
        $departmentDirector = $postVars['departmentDirector'];
        $phoneNumber = $postVars['phoneNumber'] ?? '';
        $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);

        if(General::isNullOrEmpty($postVars['department']) or General::isNullOrEmpty($postVars['departmentDirector'])){
            $request->getRouter()->redirect('/application/config-event/departments/new?status=failed');
        }

        $department = mb_strtoupper($department);

        // valida departamento já existe
        $obDepartment = EntityDepartment::getDepartmentByName($department);
        if($obDepartment instanceof EntityDepartment){
            $request->getRouter()->redirect('/application/config-event/departments/new?status=duplicated');
        }

        // Nova instancia de departamento
        $obDepartment = new EntityDepartment();
        $obDepartment->department = $department;
        $obDepartment->department_director = $departmentDirector;
        $obDepartment->phone_number = $phoneNumber;
        $obDepartment->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/departments/'.$obDepartment->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditDepartment(Request $request, int $id): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o departamento do banco de dados
        $obDepartment = EntityDepartment::getDepartmentById($id);

        // Valida instância
        if(!$obDepartment instanceof EntityDepartment){
            $request->getRouter()->redirect('/application/config-event/departments');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/departments',[
            'title' => 'Editar Departamento',
            'breadcrumbItem' => 'Editar Departamento',
            'department' => $obDepartment->department,
            'departmentDirector' => $obDepartment->department_director,
            'phoneNumber' => $obDepartment->phone_number,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de departamentos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditDepartment(Request $request, int $id): array
    {
        // Obtém o departamento do banco de dados
        $obDepartment = EntityDepartment::getDepartmentById($id);

        // Valida instância
        if(!$obDepartment instanceof EntityDepartment){
            $request->getRouter()->redirect('/application/config-event/departments');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $department = $postVars['department'] ?? '';
        $departmentDirector = $postVars['departmentDirector'];
        $phoneNumber = $postVars['phoneNumber'] ?? '';
        $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);

        if(General::isNullOrEmpty($postVars['department']) or General::isNullOrEmpty($postVars['departmentDirector'])){
            $request->getRouter()->redirect('/application/config-event/departments/new?status=failed');
        }

        $department = mb_strtoupper($department);

        // valida departamento já existe
        $obDepartment = EntityDepartment::getDepartmentByName($department);
        if($obDepartment instanceof EntityDepartment && $obDepartment->id != $id){
            $request->getRouter()->redirect('/application/config-event/departments/new?status=duplicated');
        }

        // Nova instancia de departamento
        $obDepartment = new EntityDepartment();
        $obDepartment->id = $id;
        $obDepartment->department = $department;
        $obDepartment->department_director = $departmentDirector;
        $obDepartment->phone_number = $phoneNumber;
        $obDepartment->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/departments/'.$obDepartment->id.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um departamento
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteDepartment(Request $request, int $id): string
    {
        // Obtém o level do banco de dados
        $obDepartment = EntityDepartment::getDepartmentById($id);

        // Valida instância
        if(!$obDepartment instanceof EntityDepartment){
            $request->getRouter()->redirect('/application/config-event/departments');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/delete/departments',[
            'title' => 'Excluir Departamento',
            'breadcrumbItem' => 'Excluir Departamento',
            'department' => $obDepartment->department,
            'departmentDirector' => $obDepartment->department_director,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de departamentos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por excluir de um departamento
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setDeleteDepartment(Request $request, int $id): array
    {
        // Obtém o level do banco de dados
        $obDepartment = EntityDepartment::getDepartmentById($id);

        // Valida instância
        if(!$obDepartment instanceof EntityDepartment){
            $request->getRouter()->redirect('/application/config-event/departments');
        }

        // Obtém o depoimento do banco de dados
        $obEvent = EntityEvent::getEventDepartmentById($id);

        // Valida instância
        if($obEvent instanceof EntityEvent){
            $request->getRouter()->redirect('/application/config-event/departments/'.$id.'/delete?status=rejected');
        }

        // Excluir o usuário
        $obDepartment->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event?status=deleted');

        return [ "success" => true];

    }
}