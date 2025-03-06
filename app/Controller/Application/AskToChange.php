<?php

namespace App\Controller\Application;

use App\Controller\Error\Error;
use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\DaysOfWeek as EntityDaysOfWeek;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\SoundTeam as EntitySoundTeam;
use App\Model\Entity\SoundDevice as EntitySoundDevice;
use App\Model\Entity\AskToChange as EntityAskToChange;
use App\Session\Users\Login as SessionUsersLogin;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\View;
use App\Utils\ViewJS;
use DateTime;
use DateTimeZone;
use Exception;

class AskToChange extends Page
{
    /**
     * @var array
     */
    private static array $scripts = [
        'update-ask-to-change' => [
            'script' => 'application/js/scripts/btn-update-ask-to-change',
            'timeout' => 0
        ]
    ];

    /**
     * Método responsável por retornar os scripts js
     * @return string
     */
    private static function getScripts(): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        //scripts
        $scripts = '';

        //Intera os modulos
        foreach (self::$scripts as $module) {
            $scripts .= ViewJS::render($module['script'],[
                'host' => URL,
                'timeout' => (int)$module['timeout'],
                'token' => $session['usuario']['token']
            ]);
        }

        // retornar os scripts para todas as páginas
        return View::render('application/js/view/script',[
            'scripts' => $scripts
        ]);
    }

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
     * Método responsável por lista a equipe
     * @param int|null $selected
     * @return string
     */
    private static function getWho(int $selected = null): string
    {
        // carregar a equipe
        $options = '';

        $order = 'id ASC';
        $where = null;
        $results = EntitySoundTeam::getSoundTeam($where, $order);
        while ($obSoundTeam = $results->fetchObject(EntitySoundTeam::class)){
            $options .= View::render('application/modules/manager-sound-team/forms/select',[
                'optionValue' => $obSoundTeam->id,
                'optionName' => $obSoundTeam->complete_name,
                'selected' => ($obSoundTeam->id === $selected)? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por lista a equipe
     * @param int|null $selected
     * @param string $schedulerDate
     * @return string
     */
    private static function getWho2(int $selected = null, string $schedulerDate): string
    {
        // busca escala do dia
        $data=self::getSchedulerDate($schedulerDate);

        // carregar a equipe
        $options = '';

        $order = 'id ASC';
        $where = null;
        $results = EntitySoundTeam::getSoundTeam($where, $order);
        while ($obSoundTeam = $results->fetchObject(EntitySoundTeam::class)){
            $options .= View::render('application/modules/manager-sound-team/forms/select2',[
                'optionValue' => $obSoundTeam->id,
                'optionName' => (in_array($obSoundTeam->id, $data))? $obSoundTeam->complete_name . ' (Não permitido)' : $obSoundTeam->complete_name,
                'selected' => ($obSoundTeam->id === $selected)? 'selected' : null,
                'disabled' => (in_array($obSoundTeam->id, $data))?'disabled' : null
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por lista os dispositivos
     * @param int|null $selected
     * @return string
     */
    private static function getWhere(int $selected = null): string
    {
        // carregar a dispositivos
        $options = '';

        $order = 'id ASC';
        $where = null;
        $results = EntitySoundDevice::getSoundDevice($where, $order);
        while ($obSoundDevice = $results->fetchObject(EntitySoundDevice::class)){
            $options .= View::render('application/modules/manager-sound-team/forms/select',[
                'optionValue' => $obSoundDevice->id,
                'optionName' => $obSoundDevice->device,
                'selected' => ($obSoundDevice->id === $selected)? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por autorizar o uso do botão gestão de eventos
     * @return string|null
     * @throws Exception
     */
    private static function getAllowButton($levelId, $moduleId)
    {
        $obAccessModules = EntityAccessModules::getAccessModuleByIdByLevelId($levelId, $moduleId);

        if(!$obAccessModules instanceof EntityAccessModules){
            return 'disabled';
        }

        return ($obAccessModules->allow == 'true' || $obAccessModules->allow == 1)? null:'disabled';
    }

    /**
     * @param int $userId
     * @param int $levelId
     * @param string $type
     * @return string
     * @throws Exception
     */
    private static function getAskToChangeItems(int $userId, int $levelId, string $type): string
    {
        // inicializa itens
        $itens = '';

        $currentDate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $currentDate = $currentDate->setTimezone(new DateTimeZone('GMT-3'))->format('d-m-Y');

        //$obSoundTeam = self::getUserSoundTeam($userId);
        $obSoundTeam = EntitySoundTeam::getSoundTeamByLinkedUserId($userId);

        if(!$obSoundTeam instanceof EntitySoundTeam && $levelId != -1) {
            return $itens;
        }

        $query = (!$obSoundTeam instanceof EntitySoundTeam) ? null : (($type == 'requested') ? 'current_linked_user_id = ' . $obSoundTeam->id ?? 0 : 'new_linked_user_id = ' . $obSoundTeam->id ?? 0);
        $orderBy = 'id ASC';
        $results = EntityAskToChange::getAskToChange($query, $orderBy);
        // renderiza itens
        while ($obAskToChange = $results->fetchObject(EntityAskToChange::class)){
            $schedulerDate = new DateTime($obAskToChange->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $itens .= View::render('application/modules/manager-sound-team/items/my-ask-to-change',[
                'id' => $obAskToChange->id,
                'schedulerDate' => $schedulerDate,
                'dayOfWeek' => $obAskToChange->scheduler_day_long_description,
                'newLinkedUser' => ($type == 'requested')? $obAskToChange->new_linked_user_name : $obAskToChange->current_linked_user_name,
                'where' => $obAskToChange->scheduler_sound_device_name,
                'status' => $obAskToChange->status_name,
                'comments' => $obAskToChange->comments,
                'displayCancel' => ($type == 'requested')? 'block' : 'none',
                'displayAccepted' => ($type == 'requested')? 'none' : 'block',
                'displayRejected' => ($type == 'requested')? 'none' : 'block',
                'disabledAccepted' => ( strtotime($currentDate) > strtotime($schedulerDate) or $obAskToChange->status != 1 ) ? 'disabled' : null,
                'disabledRejected' => ( strtotime($currentDate) > strtotime($schedulerDate) or $obAskToChange->status != 1 )? 'disabled' : null,
                'disabledCancel' => ($obAskToChange->status == 1)? self::getAllowButton($levelId, 91) : 'disabled'
            ]);
        }

        return $itens;
    }

    /**
     * @param int $userId
     * @return EntitySoundTeam|integer
     */
    private static function getUserSoundTeam(int $userId)
    {
        // Obtém do banco de dados
        $obSoundTeam = EntitySoundTeam::getSoundTeamByLinkedUserId($userId);

        if(!$obSoundTeam instanceof EntitySoundTeam){
            return 0;
        }

        return $obSoundTeam;
    }

    /**
     * @param Request $request
     * @param string $type
     * @return string
     * @throws Exception
     */
    public static function getListMyAskToChange(Request $request, string $type)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];
        $userId = $session['usuario']['id'];

        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/my-ask-to-change',[
            'title' => ($type == 'requested')?'Minhas Solicitações de Troca': 'Solicitações de Troca Recebidas',
            'breadcrumbItem' => ($type == 'requested')?'Minhas Solicitações de Troca': 'Solicitações de Troca Recebidas',
            'who' => ($type == 'requested')? 'Solicitado Para': 'Solicitado De',
            'items' => self::getAskToChangeItems($userId, $levelId, $type),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de minhas solicitações de troca.');

        // Retorna a pagina completa
        return parent::getPanel( $obOrganization->full_name . ' | ' . ($type == 'requested')?'Minhas Solicitações de Troca': 'Solicitações de Troca Recebidas',$content,'manager-sound-team');
    }

    /**
     * @param string $schedulerDate
     * @return array
     */
    private static function getSchedulerDate(string $schedulerDate)
    {
        // obtém escala do dia de troca
        $data = [];
        $start = $schedulerDate . ' 00:00:00';
        $end = $schedulerDate . ' 23:59:59';
        $query = 'scheduler_date BETWEEN "'.$start.'"'.' AND "'.$end.'"';
        $results = EntitySoundTeamLineup::getSoundTeamLineup($query);

        while ($obSoundTeamLineup = $results->fetchObject(EntitySoundTeamLineup::class)){
            $data[] = $obSoundTeamLineup->sound_team_id;
        }
        //Debug::debug($obSoundTeamLineup->fetchAll());

        return $data;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getAskToChange(Request $request, int $id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $userId = $session['usuario']['id'];


        // Obtém do banco de dados
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($id);

        if($obSoundTeamLineup->linked_user_id !== $userId){
            throw new Exception(Error::getError($request,403));
        }

        // Valida instância
        if(!$obSoundTeamLineup instanceof EntitySoundTeamLineup){
            $request->getRouter()->redirect('/application/manager-sound-team');
        }

        $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
        $schedulerDate = $schedulerDate->format('Y-m-d');

        $content = View::render('application/modules/manager-sound-team/forms/ask-to-change', [
            'title' => 'Pedir Troca',
            'breadcrumbItem' => 'Pedir Troca',
            'schedulerDate' => $schedulerDate,
            'who' => self::getWho($obSoundTeamLineup->sound_team_id),
            'who2' => self::getWho2($obSoundTeamLineup->sound_team_id, $schedulerDate),
            'btnTipo' => 'success',
            'btnNome' => 'Enviar Solicitação',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de solicitações de troca.');
        // Retorna a pagina completa
        return parent::getPanel( $obOrganization->full_name . ' | Pedir Troca',$content,'manager-sound-team');

    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setAskToChange(Request $request, int $id): array
    {
        // Obtém do banco de dados
        $obAskToChange = EntityAskToChange::getAskToChangeBySchedulerId($id);

//        // Valida instância
//        if(!$obAskToChange instanceof EntityAskToChange){
//            $request->getRouter()->redirect('/application/manager-sound-team');
//        }

        if($obAskToChange->status == 1){
            $request->getRouter()->redirect('/application/manager-sound-team/ask-to-change/'.$obAskToChange->scheduler_id.'?status=ask_duplicated');
        }

        //PostVars
        $postVars = $request->getPostVars();
        $soundTeamId = $postVars['who'] ?? '';

        if(General::isNullOrEmpty($postVars['who']) ){
            $request->getRouter()->redirect('/application/manager-sound-team/ask-to-change/'.$id.'?status=failed');
        }

        // Obtém do banco de dados
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($id);

        // Valida instância
        if(!$obSoundTeamLineup instanceof EntitySoundTeamLineup){
            $request->getRouter()->redirect('/application/manager-sound-team');
        }

        // Nova instancia
        $obAskToChange = new EntityAskToChange();
        $obAskToChange->current_linked_user_id = $obSoundTeamLineup->sound_team_id;
        $obAskToChange->new_linked_user_id = $soundTeamId;
        $obAskToChange->scheduler_id = $id;
        $obAskToChange->status = 1;
        $obAskToChange->comments = '';
        $obAskToChange->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/ask-to-change/'.$obAskToChange->scheduler_id.'?status=ask_created');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @param int $status
     * @return true[]
     * @throws Exception
     */
    public static function setAskToChangeStatus(Request $request, int $id, int $status): array
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $userId = $session['usuario']['id'];

        // Obtém do banco de dados
        $obAskToChange = EntityAskToChange::getAskToChangeById($id);

        if($status === 4) {
            // Obtém do banco de dados
            $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($obAskToChange->scheduler_id);

            //Debug::debug($obSoundTeamLineup);

            if($obSoundTeamLineup->linked_user_id !== $userId){
                throw new Exception(Error::getError($request,403));
            }
        }elseif ($status === 2 or $status === 3){
            // obtém os dados do usuário
            $obSoundTeam = EntitySoundTeam::getSoundTeamById($obAskToChange->new_linked_user_id);

            if($obSoundTeam->linked_user_id !== $userId){
                throw new Exception(Error::getError($request,403));
            }
        }


        if($obAskToChange->status != 1){
            $request->getRouter()->redirect('/application/manager-sound-team/ask-to-change/received?status=ask_duplicated');
        }

        // Nova instancia
        $obAskToChange = new EntityAskToChange();
        $obAskToChange->id = $id;
        $obAskToChange->status = $status;
        $obAskToChange->comments = '';
        $obAskToChange->updateStatus();

        if($status == 2){
            self::setUpdateScheduler($obAskToChange->id);
        }

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/ask-to-change/received?status=updated');

        return [ "success" => true];

    }

    /**
     * @param int $idAskToChange
     * @return true
     * @throws Exception
     */
    private static function setUpdateScheduler(int $idAskToChange)
    {
        // recupera dados de troca
        $obAskToChange = EntityAskToChange::getAskToChangeById($idAskToChange);

        $newLinkedUserId = $obAskToChange->new_linked_user_id;
        $schedulerId = $obAskToChange->scheduler_id;

        // Obtém do banco de dados
        $obSoundTeamLineup = new EntitySoundTeamLineup;
        $obSoundTeamLineup->id = $schedulerId;
        $obSoundTeamLineup->sound_team_id = $newLinkedUserId;
        $obSoundTeamLineup->updateNewLinkedUser();

        return true;
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
            case 'ask_created':
                return Alert::getSuccess('Solicitação enviada com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Registro atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Registro excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getWarning('Registro duplicado!');
                break;
            case 'ask_duplicated':
                return Alert::getWarning('Solicitação de troca já efetuada, aguarde o retorno ou procure o administrador do sistema para cancelar a solicitação.');
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