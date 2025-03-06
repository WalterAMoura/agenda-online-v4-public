<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\View;
use App\Model\Entity\Event as EntityEvent;
use App\Model\Entity\EventProgram as EntityEventProgram;
use App\Utils\General;
use Exception;

class Program extends Page
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
                return Alert::getSuccess('Programa ou Evento Especial criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Programa ou Evento Especial atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Programa ou Evento Especial excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Programa ou Evento Especial digitado já exite!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar programa ou evento especial!');
                break;
            case 'rejected':
                return Alert::getWarning('Programa ou Evento Especial não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewProgram(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/programs',[
            'title' => 'Cadastrar Programa ou  Evento Especial',
            'breadcrumbItem' => 'Cadastrar Programa',
            'program' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de programações ou eventos especiais.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewProgram(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $program = $postVars['program'] ?? '';

        if(General::isNullOrEmpty($postVars['program'])){
            $request->getRouter()->redirect('/application/config-event/programs/new?status=failed');
        }

        $program = mb_strtoupper($program);

        // valida departamento já existe
        $obProgram = EntityEventProgram::getProgramEventByName($program);
        if($obProgram instanceof EntityEventProgram){
            $request->getRouter()->redirect('/application/config-event/programs/new?status=duplicated');
        }

        // Nova instancia de departamento
        $obProgram = new EntityEventProgram();
        $obProgram->description = $program;
        $obProgram->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/programs/'.$obProgram->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditProgram(Request $request, int $id): string
    {
        // Obtém o programa do banco de dados
        $obProgram = EntityEventProgram::getProgramEventById($id);

        // Valida instância
        if(!$obProgram instanceof EntityEventProgram){
            $request->getRouter()->redirect('/application/config-event/programs?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/programs',[
            'title' => 'Editar Programa ou  Evento Especial',
            'breadcrumbItem' => 'Editar Programa',
            'program' => $obProgram->description,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de programações ou eventos especiais.');

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
    public static function setEditProgram(Request $request, int $id): array
    {
        // Obtém o programa do banco de dados
        $obProgram = EntityEventProgram::getProgramEventById($id);

        // Valida instância
        if(!$obProgram instanceof EntityEventProgram){
            $request->getRouter()->redirect('/application/config-event/programs?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $program = $postVars['program'] ?? '';

        if(General::isNullOrEmpty($postVars['program'])){
            $request->getRouter()->redirect('/application/config-event/programs/new?status=failed');
        }

        $program = mb_strtoupper($program);

        // valida program já existe
        $obProgram = EntityEventProgram::getProgramEventByName($program);
        if($obProgram instanceof EntityEventProgram && $obProgram->id != $id){
            $request->getRouter()->redirect('/application/config-event/programs/new?status=duplicated');
        }

        // Nova instancia de programa
        $obProgram = new EntityEventProgram();
        $obProgram->id = $id;
        $obProgram->description = $program;
        $obProgram->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/programs/'.$obProgram->id.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um departamento
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteProgram(Request $request, int $id): string
    {
        // Obtém o programa do banco de dados
        $obProgram = EntityEventProgram::getProgramEventById($id);

        // Valida instância
        if(!$obProgram instanceof EntityEventProgram){
            $request->getRouter()->redirect('/application/config-event/programs?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/delete/programs',[
            'title' => 'Excluir Programa ou Evento Especial',
            'breadcrumbItem' => 'Excluir Departamento',
            'program' => $obProgram->description,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de programações ou eventos especiais.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por excluir de um departamento
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setDeleteProgram(Request $request, int $id): array
    {
        // Obtém o level do banco de dados
        $obProgram = EntityEventProgram::getProgramEventById($id);

        // Valida instância
        if(!$obProgram instanceof EntityEventProgram){
            $request->getRouter()->redirect('/application/config-event/programs?status=failed');
        }

        // Obtém o program do banco de dados
        $obEvent = EntityEvent::getEventProgramById($id);

        // Valida instância
        if($obEvent instanceof EntityEvent){
            $request->getRouter()->redirect('/application/config-event/programs/'.$id.'/delete?status=rejected');
        }

        // Excluir o usuário
        $obProgram->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/programs?status=deleted');

        return [ "success" => true];

    }
}