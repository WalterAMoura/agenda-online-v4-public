<?php

namespace App\Controller\Application;

use App\Controller\Application\Alert;
use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Session\Users\Login as SessionUsersLogin;
use App\Session\Error\Error as SessionMsgError;
use App\Utils\Debug;
use App\Utils\View;
use App\Model\Entity\ViewAccessModules as EntityViewAccessModules;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Modules as EntityModules;
use App\Model\Entity\Level as EntityLevel;
use App\Model\Entity\TypeModules as EntityTypeModules;
use App\Utils\General;
use DateTime;
use DateTimeZone;
use Exception;

class AccessModule extends Page
{
    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string|void
     */
    private static function getStatus(Request $request)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionMsgError::getDataSession();
        $msg = $session['error']['msg'];

        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'created':
                return Alert::getSuccess('Módulos associados com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Módulos atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Módulos excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Módulo digitado já exite!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar ou inserir módulos!\n'. $msg);
                break;
            case 'rejected':
                return Alert::getWarning('Módulos não pode ser apagado, porque já está em uso!\n'. $msg);
                break;
        }
    }

    /**
     * Método responsável por lista os anciãos
     * @param string|null $selected
     * @return string
     */
    private static function getAccessModules(string $selected = null, int $typeId)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // carregar módulos
        $options = '';

        $names = mb_split(',',$selected);
        $names_final = array();

        //remove os espaços em branco
        foreach ($names as $name){
            $names_final[] = trim($name);
        }

        $order = 'id ASC';
        $where = ($levelId === -1)?'type_id = '. $typeId : 'type_id = '. $typeId . ' AND allow_sysadmin = 0';
        $results = EntityModules::getModules($where, $order);
        while ($obModules = $results->fetchObject(EntityAccessModules::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obModules->id,
                'optionName' => $obModules->module,
                'selected' => (in_array($obModules->module, $names_final))? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por lista os meses
     * @param string|null $selected
     * @return string
     */
    private static function getLevels(string $selected = null)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        // carregar meses
        $options = '';

        $results = EntityLevel::getLevels('id >= '.$levelId.' AND id <> 0');
        while ($obLevels = $results->fetchObject(EntityLevel::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obLevels->id,
                'optionName' => $obLevels->description,
                'selected' => ($selected == $obLevels->id)? 'selected' : null
            ]);
        }

        return $options;
    }

    /**
     * @return string
     */
    private static function getFormGroupAccessModule(string $selected = null)
    {
        $formGroup = '';

        $results = EntityTypeModules::getTypeModules();
        while ($obTypeModules = $results->fetchObject(EntityTypeModules::class)){
            $formGroup .= View::render('application/modules/advanced-settings/forms/form-group-select',[
                'accessModulesId' => $obTypeModules->type,
                'labelAccessModule' => $obTypeModules->description,
                'optaccessModules' => self::getAccessModules($selected, $obTypeModules->id),
                'moduleName' => $obTypeModules->type
            ]);
        }

        return $formGroup;
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewAccessModules(Request $request): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();
        
        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/access-module',[
            'title' => 'Associar Módulos',
            'breadcrumbItem' => 'Associar Módulos',
            'formGroup' => self::getFormGroupAccessModule(),
            'optLevels' => self::getLevels(),
            'disabled' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de associar módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewAccessModules(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $accessModules = $postVars['accessModules'];
        $level = $postVars['level'];

        // valida se os campos estão vazios
        if(General::isArray($postVars['accessModules']) or General::isNullOrEmpty($postVars['level']) ){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules/new?status=failed');
        }

        // valida se já existe módulos para esse nivel
        $obAccessModules = EntityViewAccessModules::getViewAccessModulesByLevelId($level);
        if($obAccessModules instanceof EntityAccessModules){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules/new?status=duplicated');
        }

        // intera em cada ancião recebido para realizar o insert
        foreach ($accessModules as $module){
            $obAccessModules = new EntityAccessModules();
            $obAccessModules->module_id = $module;
            $obAccessModules->level_id = $level;
            $obAccessModules->allow = true;
            $obAccessModules->cadastrar();
        }

        $request->getRouter()->redirect('/application/advanced-settings/access-modules/'.$obAccessModules->level_id.'/edit?status=created');

        // retorno de sucesso
        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getEditAccessModule(Request $request, int $id): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();
        
        // valida escala do mes e ano
        $obAccessModules = EntityViewAccessModules::getViewAccessModulesByLevelId($id);

        if(!$obAccessModules instanceof EntityViewAccessModules){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/access-module',[
            'title' => 'Editar Módulos Associados',
            'breadcrumbItem' => 'Editar Módulos Associados',
            'optLevels' => self::getLevels($obAccessModules->level_id),
            'formGroup' => self::getFormGroupAccessModule($obAccessModules->module),
            'disabled' => 'disabled',
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de associar módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param int $id
     * @return array
     * @throws Exception
     */
    public static function setEditAccessModule(Request $request, int $id): array
    {
        // valida módulos associados
        $obAccessModules = EntityViewAccessModules::getViewAccessModulesByLevelId($id);

        if(!$obAccessModules instanceof EntityViewAccessModules){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $accessModules = $postVars['accessModules'];
        $level = $postVars['level']??$id;

        // valida se os campos estão vazios
        if(General::isArray($postVars['accessModules']) or General::isNullOrEmpty($level) ){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules/'.$level.'/edit?status=failed');
        }

        // exclui os registros
        $results = EntityAccessModules::getAccessModules('level_id = '.$level);
        while($objt = $results->fetchObject(EntityAccessModules::class)){
            $obAccessModules = new EntityAccessModules();
            $obAccessModules->id = $objt->id;
            $obAccessModules->excluir();
        }

        // intera em cada ancião recebido para realizar o insert
        foreach ($accessModules as $module){
            $obAccessModules = new EntityAccessModules();
            $obAccessModules->module_id = $module;
            $obAccessModules->level_id = $level;
            $obAccessModules->allow = true;
            $obAccessModules->cadastrar();
        }

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/access-modules/'.$level.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um departamento
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteAccessModule(Request $request, int $id): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();
        
        // valida módulos associados
        $obAccessModules = EntityViewAccessModules::getViewAccessModulesByLevelId($id);

        if(!$obAccessModules instanceof EntityViewAccessModules){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules?status=failed');
        }


        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/access-module',[
            'title' => 'Excluir Escala',
            'breadcrumbItem' => 'Excluir Escala',
            'levelName' => $obAccessModules->description,
            'accessModules' => $obAccessModules->module,
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de associar módulos.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações Avançadas',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um módulo
     * @param Request $request
     * @param int $id
     * @return array
     */
    public static function setDeleteAccessModule(Request $request, int $id): array
    {
        // valida módulos associados
        $obAccessModules = EntityViewAccessModules::getViewAccessModulesByLevelId($id);

        if(!$obAccessModules instanceof EntityViewAccessModules){
            $request->getRouter()->redirect('/application/advanced-settings/access-modules?status=failed');
        }


        // exclui os registros
        $results = EntityAccessModules::getAccessModules('level_id = '.$id);
        while($objt = $results->fetchObject(EntityAccessModules::class)){
            $obAccessModules = new EntityAccessModules();
            $obAccessModules->id = $objt->id;
            $obAccessModules->excluir();
        }

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/access-modules?status=deleted');

        return [ "success" => true];

    }
}