<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\Pagination;
use App\Utils\View;
use App\Model\Entity\Level as EntityLevel;
use App\Model\Entity\User as EntityUser;
use App\Utils\General;
use Exception;

class Level extends Page
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
                return Alert::getSuccess('Level criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Level atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Level excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O level ou descrição digitado existe!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar level!');
                break;
            case 'rejected':
                return Alert::getWarning('Este level não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewLevel($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('/application/modules/advanced-settings/forms/level',[
            'title' => 'Cadastrar Nivel',
            'breadcrumbItem' => 'Cadastrar Nivel',
            'description' => null,
            'level' => null,
            'homePath' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de level.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Level',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewLevel(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $description = $postVars['description'] ?? '';
        $level = $postVars['level'] ?? '';
        $homePath = $postVars['homePath'] ?? '';

        if(General::isNullOrEmpty($postVars['level']) or General::isNullOrEmpty($postVars['description']) or General::isNullOrEmpty($postVars['homePath'])){
            $request->getRouter()->redirect('/application/advanced-settings/level/new?status=failed');
        }

        // valida descrição
        $obLevelByName = EntityLevel::getLevelByName($description);
        if($obLevelByName instanceof EntityLevel){
            $request->getRouter()->redirect('/application/advanced-settings/level/new?status=duplicated');
        }

        // valida o level
        $obLevelByLevel = EntityLevel::getLevelByLevel($level);
        if($obLevelByLevel instanceof EntityLevel){
            $request->getRouter()->redirect('/application/advanced-settings/level/new?status=duplicated');
        }

        // Nova instancia de level
        $obLevel = new EntityLevel();
        $obLevel->description = $description;
        $obLevel->level = $level;
        $obLevel->home_path = $homePath;
        $obLevel->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/level/'.$obLevel->id.'/edit?status=created');
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditLevel($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o level do banco de dados
        $obLevel = EntityLevel::getLevelById($id);

        // Valida instância
        if(!$obLevel instanceof EntityLevel){
            $request->getRouter()->redirect('/application/advanced-settings/level?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('/application/modules/advanced-settings/forms/level',[
            'title' => 'Editar Nivel',
            'breadcrumbItem' => 'Editar Nivel',
            'description' => $obLevel->description,
            'level' => $obLevel->level,
            'homePath' => $obLevel->home_path,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de level.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Level',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setEditLevel($request,$id)
    {
        // Obtém o level do banco de dados
        $obLevel = EntityLevel::getLevelById($id);

        // Valida instância
        if(!$obLevel instanceof EntityLevel){
            $request->getRouter()->redirect('/application/advanced-settings/level?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $description = $postVars['description'] ?? '';
        $level = $postVars['level'] ?? '';
        $homePath = $postVars['homePath'] ?? '';

        if(General::isNullOrEmpty($postVars['level']) or General::isNullOrEmpty($postVars['description']) or General::isNullOrEmpty($postVars['homePath'])){
            $request->getRouter()->redirect('/application/advanced-settings/level/new?status=failed');
        }

        // valida descrição
        $obLevelByName = EntityLevel::getLevelByName($description);
        if($obLevelByName instanceof EntityLevel && $obLevelByName->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/level/'.$id.'/edit?status=duplicated');
        }

        // valida o level
        $obLevelByLevel = EntityLevel::getLevelByLevel($level);
        if($obLevelByLevel instanceof EntityLevel && $obLevelByLevel->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/level/'.$id.'/edit?status=duplicated');
        }

        // Nova instancia de level
        $obLevel = new EntityLevel();
        $obLevel->id = $id;
        $obLevel->description = $description;
        $obLevel->level = $level;
        $obLevel->home_path = $homePath;
        $obLevel->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/level/'.$obLevel->id.'/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteLevel($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o level do banco de dados
        $obLevel = EntityLevel::getLevelById($id);

        // Valida instância
        if(!$obLevel instanceof EntityLevel){
            $request->getRouter()->redirect('/application/advanced-settings/level?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('/application/modules/advanced-settings/delete/level',[
            'title' => 'Excluir Nivel',
            'breadcrumbItem' => 'Excluir Nivel',
            'nome' => $obLevel->description,
            'level' => $obLevel->level,
            'path' => $obLevel->home_path,
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de level.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Level',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public static function setDeleteLevel($request,$id)
    {
        // Obtém o level do banco de dados
        $obLevel = EntityLevel::getLevelById($id);

        // Valida instância
        if(!$obLevel instanceof EntityLevel){
            $request->getRouter()->redirect('/application/advanced-settings/level?status=failed');
        }

        // Obtém o usuário level do banco de dados
        $obUser= EntityUser::getUserByIdLevel($id);

        // Valida instância
        if($obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/level/'.$id.'/delete?status=rejected');
        }

        // Excluir o usuário
        $obLevel->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings?status=deleted');

    }
}