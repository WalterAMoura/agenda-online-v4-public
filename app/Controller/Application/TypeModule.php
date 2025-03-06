<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\Debug;
use App\Utils\Pagination;
use App\Utils\View;
use App\Model\Entity\Level as EntityLevel;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Model\Entity\Modules as EntityModules;
use App\Utils\General;
use Exception;

class TypeModule extends Page
{
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
                return Alert::getSuccess('Tipo módulo criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Tipo módulo atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Tipo módulo excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O tipo módulo digitado existe!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar tipo módulo!');
                break;
            case 'rejected':
                return Alert::getWarning('Este tipo módulo não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por lista os meses
     * @param string|null $selected
     * @return string
     */
    private static function getTypeModules(string $selected = null)
    {
        // carregar meses
        $options = '';

        $results = EntityTypeModules::getTypeModules('id > 0');
        while ($obTypeModules = $results->fetchObject(EntityTypeModules::class)){
            $options .= View::render('/application/modules/advanced-settings/forms/select',[
                'optionValue' => $obTypeModules->id,
                'optionName' => $obTypeModules->type,
                'selected' => ($selected == $obTypeModules->id)? 'selected' : null
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
    public static function getNewTypeModule($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('/application/modules/advanced-settings/forms/type-module',[
            'title' => 'Cadastrar Tipo Módulo',
            'breadcrumbItem' => 'Cadastrar Tipo Módulo',
            'descriptionModule' => null,
            'typeModule' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página tipo módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewTypeModule(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $typeModule = $postVars['typeModule'] ?? '';
        $descriptionModule = $postVars['descriptionModule'] ?? '';

        if(General::isNullOrEmpty($postVars['typeModule']) or General::isNullOrEmpty($postVars['descriptionModule'])){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/new?status=failed');
        }

        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleByType($typeModule);
        if($obTypeModule instanceof EntityTypeModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/new?status=duplicated');
        }

        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleByDescription($descriptionModule);
        if($obTypeModule instanceof EntityTypeModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/new?status=duplicated');
        }


        // Nova instancia de level
        $obTypeModule = new EntityTypeModules();
        $obTypeModule->type = $typeModule;
        $obTypeModule->description = $descriptionModule;
        $obTypeModule->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/type-module/'.$obTypeModule->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditTypeModule($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleById($id);

        // Valida instância
        if(!$obTypeModule instanceof EntityTypeModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('/application/modules/advanced-settings/forms/type-module',[
            'title' => 'Editar Tipo Módulo',
            'breadcrumbItem' => 'Editar Tipo Módulo',
            'descriptionModule' => $obTypeModule->description,
            'typeModule' => $obTypeModule->type,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página tipo módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditTypeModule($request,$id)
    {
        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleById($id);

        // Valida instância
        if(!$obTypeModule instanceof EntityTypeModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $typeModule = $postVars['typeModule'] ?? '';
        $descriptionModule = $postVars['descriptionModule'] ?? '';

        if(General::isNullOrEmpty($postVars['typeModule']) or General::isNullOrEmpty($postVars['descriptionModule'])){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/new?status=failed');
        }

        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleByType($typeModule);
        if($obTypeModule instanceof EntityTypeModules && $obTypeModule->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/new?status=duplicated');
        }

        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleByDescription($descriptionModule);
        if($obTypeModule instanceof EntityTypeModules && $obTypeModule->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/new?status=duplicated');
        }


        // Nova instancia de level
        $obTypeModule = new EntityTypeModules();
        $obTypeModule->id = $id;
        $obTypeModule->type = $typeModule;
        $obTypeModule->description = $descriptionModule;
        $obTypeModule->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/type-module/'.$obTypeModule->id.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteTypeModule($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleById($id);

        // Valida instância
        if(!$obTypeModule instanceof EntityTypeModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('/application/modules/advanced-settings/delete/type-module',[
            'title' => 'Excluir Tipo Módulo',
            'breadcrumbItem' => 'Excluir Tipo Módulo',
            'typeModule' => $obTypeModule->type,
            'descriptionModule' => $obTypeModule->description,
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página tipo módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setDeleteTypeModule($request,$id)
    {
        // valida descrição
        $obTypeModule = EntityTypeModules::getTypeModuleById($id);

        // Valida instância
        if(!$obTypeModule instanceof EntityTypeModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module?status=failed');
        }

        $obModule = EntityModules::getModuleByTypeModuleId($obTypeModule->id);
        if($obModule instanceof EntityModules){
            $request->getRouter()->redirect('/application/advanced-settings/type-module/'.$obTypeModule->id.'/delete?status=rejected');
        }

        // Excluir o usuário
        $obTypeModule->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/type-module?status=deleted');

        return [ "success" => true];

    }
}