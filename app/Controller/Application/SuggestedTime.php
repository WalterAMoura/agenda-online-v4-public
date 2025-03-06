<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\DaysOfWeek as EntityDaysOfWeek;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SuggestedTime as EntitySuggestedTime;
use App\Utils\General;
use App\Utils\View;
use Exception;

class SuggestedTime extends Page
{

    /**
     * Método responsável por lista os anciãos
     * @param string|null $selected
     * @return string
     */
    private static function getDayOfWeek(string $selected = null): string
    {
        // carregar dias da semana
        $options = '';

        $order = 'id ASC';
        $where = null;
        $results = EntityDaysOfWeek::getDaysOfWeek($where, $order);
        while ($obDaysOfWeek = $results->fetchObject(EntityDaysOfWeek::class)){
            $options .= View::render('application/modules/manager-sound-team/forms/select',[
                'optionValue' => $obDaysOfWeek->id,
                'optionName' => $obDaysOfWeek->long_description,
                'selected' => ($obDaysOfWeek->long_description === $selected)? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por retornar o formulário de cadastro
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewSuggestedTime(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/suggested-time',[
            'title' => 'Cadastrar Horário Sugerido',
            'breadcrumbItem' => 'Cadastrar Horário Sugerido',
            'dayOfWeek' => self::getDayOfWeek(),
            'suggestedTime' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de horário sugerido.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Horários Sugeridos',$content,'manager-sound-team');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setSuggestedTime(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $day = $postVars['dayOfWeek'] ?? '';
        $suggestedTime = $postVars['suggestedTime'] ?? '';

        if(General::isNullOrEmpty($postVars['suggestedTime']) or General::isNullOrEmpty($postVars['dayOfWeek'])){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time/new?status=failed');
        }

        // valida se já existe
        $obSuggestedTime = EntitySuggestedTime::getSuggestedByDayOfWeek($day);
        if($obSuggestedTime instanceof EntitySuggestedTime){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time/new?status=duplicated');
        }

        // Nova instancia
        $obSuggestedTime = new EntitySuggestedTime();
        $obSuggestedTime->suggested_time = $suggestedTime;
        $obSuggestedTime->day_of_week_id = $day;
        $obSuggestedTime->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/suggested-time/'.$obSuggestedTime->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditSuggestedTime(Request $request, int $id): string
    {
        // Obtém do banco de dados
        $obSuggestedTime = EntitySuggestedTime::getSuggestedTimeById($id);

        // Valida instância
        if(!$obSuggestedTime instanceof EntitySuggestedTime){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/suggested-time',[
            'title' => 'Editar Horário Sugerido',
            'breadcrumbItem' => 'Editar Horário Sugerido',
            'dayOfWeek' => self::getDayOfWeek($obSuggestedTime->long_description),
            'suggestedTime' =>$obSuggestedTime->suggested_time,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de horário sugerido.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Horários Sugeridos',$content,'manager-sound-team');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditSuggestedTime(Request $request, int $id): array
    {
        // Obtém do banco de dados
        $obSuggestedTime = EntitySuggestedTime::getSuggestedTimeById($id);

        // Valida instância
        if(!$obSuggestedTime instanceof EntitySuggestedTime){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time?status=failed');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $day = $postVars['dayOfWeek'] ?? '';
        $suggestedTime = $postVars['suggestedTime'] ?? '';

        if(General::isNullOrEmpty($postVars['suggestedTime']) or General::isNullOrEmpty($postVars['dayOfWeek'])){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time/new?status=failed');
        }

        // valida já existe
        $obSuggestedTime = EntitySuggestedTime::getSuggestedByDayOfWeek($day);
        if($obSuggestedTime instanceof EntitySuggestedTime && $obSuggestedTime->id != $id){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time/new?status=duplicated');
        }

        // Nova instancia
        $obSuggestedTime = new EntitySuggestedTime();
        $obSuggestedTime->id = $id;
        $obSuggestedTime->suggested_time = $suggestedTime;
        $obSuggestedTime->day_of_week_id = $day;
        $obSuggestedTime->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/suggested-time/'.$obSuggestedTime->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteSuggestedTime(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obSuggestedTime = EntitySuggestedTime::getSuggestedTimeById($id);

        // Valida instância
        if(!$obSuggestedTime instanceof EntitySuggestedTime){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time?status=failed');
        }

        $content = View::render('application/modules/manager-sound-team/delete/suggested-time', [
            'title' => 'Excluir Horário Sugerido',
            'breadcrumbItem' => 'Excluir Horário Sugerido',
            'dayOfWeek' => $obSuggestedTime->long_description,
            'suggestedTime' => $obSuggestedTime->suggested_time,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de horário sugerido.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Horários Sugeridos',$content,'manager-sound-team');

    }

    public static function setDeleteSuggestedTime(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obSuggestedTime = EntitySuggestedTime::getSuggestedTimeById($id);

        // Valida instância
        if(!$obSuggestedTime instanceof EntitySuggestedTime){
            $request->getRouter()->redirect('/application/manager-sound-team/suggested-time?status=failed');
        }

        $obSuggestedTime->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/suggested-time?status=deleted');

        return [ "success" => true];
    }

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
                return Alert::getSuccess('Registro criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Registro atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Registro excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getWarning('Este dia da semana já possui uma configuração!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar registro!');
                break;
            case 'rejected':
                return Alert::getWarning('Este registro não pode ser apagado, porque já está em uso!');
                break;
        }
    }
}