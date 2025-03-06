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

class Module extends Page
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
                return Alert::getSuccess('Módulo criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Módulo atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Módulo excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O módulo digitado existe!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar módulo!');
                break;
            case 'rejected':
                return Alert::getWarning('Este módulo não pode ser apagado, porque já está em uso!');
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
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obTypeModules->id,
                'optionName' => $obTypeModules->type,
                'selected' => ((int)$selected === (int)$obTypeModules->id)? 'selected' : null
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
    public static function getNewModule($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/module',[
            'title' => 'Cadastrar Módulo',
            'breadcrumbItem' => 'Cadastrar Módulo',
            'module' => null,
            'labelModule' => null,
            'iconModule' => null,
            'modulePath' => null,
            'optTypeModule' => self::getTypeModules(),
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewModule(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $module = $postVars['module'] ?? '';
        $labelModule = $postVars['labelModule'] ?? '';
        $iconModule = $postVars['iconModule'] ?? '';
        $typeModule = $postVars['typeModule'] ?? '';
        $modulePath = $postVars['modulePath'] ?? '';

        if(General::isNullOrEmpty($postVars['module']) or General::isNullOrEmpty($postVars['labelModule']) or General::isNullOrEmpty($postVars['iconModule']) or General::isNullOrEmpty( $postVars['typeModule']) or General::isNullOrEmpty($postVars['modulePath'])){
            $request->getRouter()->redirect('/application/advanced-settings/modules/new?status=failed');
        }

        // valida descrição
        $obModule = EntityModules::getModuleByName($module);
        if($obModule instanceof EntityModules){
            $request->getRouter()->redirect('/application/advanced-settings/modules/new?status=duplicated');
        }


        // Nova instancia de level
        $obModule = new EntityModules();
        $obModule->module = $module;
        $obModule->label = $labelModule;
        $obModule->icon = $iconModule;
        $obModule->type_id = $typeModule;
        $obModule->path_module = $modulePath;
        $obModule->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/modules/'.$obModule->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditModule($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // valida descrição
        $obModule = EntityModules::getModuleById($id);

        // Valida instância
        if(!$obModule instanceof EntityModules){
            $request->getRouter()->redirect('/application/advanced-settings/module?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/module',[
            'title' => 'Editar Módulo',
            'breadcrumbItem' => 'Editar Módulo',
            'module' => $obModule->module,
            'labelModule' => $obModule->label,
            'iconModule' => $obModule->icon,
            'modulePath' => $obModule->path_module,
            'optTypeModule' => self::getTypeModules($obModule->type_id),
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de módulos.');

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
    public static function setEditModule($request,$id)
    {
        // valida descrição
        $obModule = EntityModules::getModuleById($id);

        // Valida instância
        if(!$obModule instanceof EntityModules){
            $request->getRouter()->redirect('/application/advanced-settings/module?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $module = $postVars['module'] ?? '';
        $labelModule = $postVars['labelModule'] ?? '';
        $iconModule = $postVars['iconModule'] ?? '';
        $typeModule = $postVars['typeModule'] ?? '';
        $modulePath = $postVars['modulePath'] ?? '';

        if(General::isNullOrEmpty($postVars['module']) or General::isNullOrEmpty($postVars['labelModule']) or General::isNullOrEmpty($postVars['iconModule']) or General::isNullOrEmpty( $postVars['typeModule']) or General::isNullOrEmpty($postVars['modulePath'])){
            $request->getRouter()->redirect('/application/advanced-settings/modules/new?status=failed');
        }

        // valida descrição
        $obModule = EntityModules::getModuleByName($module);
        if($obModule instanceof EntityModules && $obModule->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/modules/'.$id.'/edit?status=duplicated');
        }

        // Nova instancia de level
        $obModule = new EntityModules();
        $obModule->id = $id;
        $obModule->module = $module;
        $obModule->label = $labelModule;
        $obModule->icon = $iconModule;
        $obModule->type_id = $typeModule;
        $obModule->path_module = $modulePath;
        $obModule->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/modules/'.$obModule->id.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteModule($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // valida descrição
        $obModule = EntityModules::getModuleById($id);

        // Valida instância
        if(!$obModule instanceof EntityModules){
            $request->getRouter()->redirect('/application/advanced-settings/module?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/module',[
            'title' => 'Excluir Módulo',
            'breadcrumbItem' => 'Excluir Módulo',
            'moduleLabel' => $obModule->label,
            'module' => $obModule->module,
            'pathModule' => $obModule->path_module,
            'typeModule' => $obModule->type,
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setDeleteModule($request,$id)
    {
        // valida descrição
        $obModule = EntityModules::getModuleById($id);

        // Valida instância
        if(!$obModule instanceof EntityModules){
            $request->getRouter()->redirect('/application/advanced-settings/module?status=failed');
        }

        // Excluir o usuário
        $obModule->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/module?status=deleted');

        return [ "success" => true];

    }
}