<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Departaments as EntityDepartments;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\Elder as EntityElder;
use App\Model\Entity\ElderForDepartment as EntityElderForDepartment;
use App\Utils\Debug;
use App\Utils\View;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class ElderForDepartment extends Page
{


    /**
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getDepartments(Request $request, string $selected = null)
    {
        // status agendamento
        $options = '';
        $order = isset($selected)? 'order_status' : 'department ASC';
        $where = 'id > 1 or id = 0';;
        $query = isset($selected)? 'id, department, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityDepartments::getDepartments($where,$order,null,null,$query);
        while ($obDepartment = $results->fetchObject(EntityDepartments::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obDepartment->id,
                'optionName' => $obDepartment->department
            ]);
        }

        return $options;
    }

    /**
     * @param Request $request
     * @param string|null $selected
     * @return string
     */
    private static function getElders(Request $request, string $selected = null)
    {
        //Debug::debug($selected);
        // status agendamento
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = null;
        $query = isset($selected)? 'id, complete_name, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityElder::getElders($where,$order,null,null,$query);
        while ($obEnder = $results->fetchObject(EntityElder::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obEnder->id,
                'optionName' => $obEnder->complete_name
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewElderForDepartment($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/elder-for-department',[
            'title' => 'Cadastrar Ancião Conselheiro',
            'breadcrumbItem' => 'Cadastrar Ancião Conselheiro',
            'departments' => self::getDepartments($request,0),
            'elders' => self::getElders($request),
            'required' => 'required',
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página associar ancião conselheiro.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Eventos',$content,'config-event');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewElderForDepartment(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $elder = $postVars['elder'] ?? '';
        $department = $postVars['department'] ?? '';

        // Obtém o depoimento do banco de dados
        $_obElderForDepartment = EntityElderForDepartment::getElderForDepartmentByDepartmentId($department);

        // inicia nova instancia
        $obElderForDepartment = new EntityElderForDepartment();
        $obElderForDepartment->department_id = $department;
        $obElderForDepartment->elder_id = $elder;

        // Valida instância
        if(!$_obElderForDepartment instanceof EntityElderForDepartment){
            $obElderForDepartment->cadastrar();
            // Redireciona
            $request->getRouter()->redirect('/application/config-event/elder-for-department/'.$obElderForDepartment->id.'/edit?status=created');
        }else{
            $obElderForDepartment->id = $_obElderForDepartment->id;
            $obElderForDepartment->atualizar();
            // Redireciona
            $request->getRouter()->redirect('/application/config-event/elder-for-department/'.$obElderForDepartment->id.'/edit?status=updated');
        }

        return [ 'success' => true ];
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
                return Alert::getSuccess('Registro criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Registro atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Registro excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Registro digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Você não pode excluir seu próprio registro!');
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
    public static function getEditElderForDepartment($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obElderForDepartment = EntityElderForDepartment::getElderForDepartmentById($id);
        // Valida instância
        if(!$obElderForDepartment instanceof EntityElderForDepartment){
            $request->getRouter()->redirect('/application/config-event/elder-for-department?status=failed');
        }
        //Debug::debug($obElderForDepartment);
        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/elder-for-department',[
            'title' => 'Editar Ancião Conselheiro',
            'breadcrumbItem' => 'Editar Ancião Conselheiro',
            'departments' => self::getDepartments($request, $obElderForDepartment->department_id),
            'elders' => self::getElders($request, $obElderForDepartment->elder_id),
            'disabled' => 'disabled',
            'required' => 'required',
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página associar ancião conselheiro.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Eventos',$content,'config-event');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditElderForDepartment($request,$id)
    {
        // Obtém o depoimento do banco de dados
        $obElderForDepartment = EntityElderForDepartment::getElderForDepartmentById($id);

        // Valida instância
        if(!$obElderForDepartment instanceof EntityElderForDepartment){
            $request->getRouter()->redirect('/application/config-event/elder-for-department?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $elder = $postVars['elder'] ?? '';
        $department = $obElderForDepartment->department_id;

        // Nova instancia de usuários
        $obElderForDepartment = new EntityElderForDepartment();
        $obElderForDepartment->id = $id;
        $obElderForDepartment->department_id = $department;
        $obElderForDepartment->elder_id = $elder;
        $obElderForDepartment->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder-for-department/'.$obElderForDepartment->id.'/edit?status=updated');

        return [ 'success' => true ];

    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteElderForDepartment($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obElderForDepartment = EntityElderForDepartment::getElderForDepartmentById($id);

        // Valida instância
        if(!$obElderForDepartment instanceof EntityElderForDepartment){
            $request->getRouter()->redirect('/application/config-event/elder-for-department?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/delete/elder-for-department',[
            'title' => 'Excluir Ancião Conselheiro',
            'breadcrumbItem' => 'Excluir Ancião Conselheiro',
            'department' => $obElderForDepartment->department_name,
            'elder' => $obElderForDepartment->complete_name,
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página associar ancião por departamento.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Eventos',$content,'config-event');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setDeleteElderForDepartment($request,$id)
    {
        // Obtém o depoimento do banco de dados
        $obElderForDepartment = EntityElderForDepartment::getElderForDepartmentById($id);

        // Valida instância
        if(!$obElderForDepartment instanceof EntityElderForDepartment){
            $request->getRouter()->redirect('/application/config-event/elder-for-department?status=failed');
        }

        $obElderForDepartment = new EntityElderForDepartment();
        $obElderForDepartment->id = $id;
        $obElderForDepartment->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder-for-department?status=deleted');

        return [ 'success' => true ];
    }
}