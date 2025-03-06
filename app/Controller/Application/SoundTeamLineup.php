<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\DaysOfWeek as EntityDaysOfWeek;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SuggestedTime as EntitySuggestedTime;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\SoundTeam as EntitySoundTeam;
use App\Model\Entity\SoundDevice as EntitySoundDevice;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\View;
use DateTime;
use DateTimeZone;
use Exception;

class SoundTeamLineup extends Page
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
     * Método responsável por retornar o formulário de cadastro
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewSoundTeamLineup(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/sound-team-lineup',[
            'title' => 'Cadastrar Agenda Equipe',
            'breadcrumbItem' => 'Cadastrar Agenda Equipe',
            'who' => self::getWho(),
            'where' => self::getWhere(),
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
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de escala sonoplastia.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Cadastrar Agenda Equipe',$content,'manager-sound-team');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setSoundTeamLineup(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $schedulerDate = $postVars['schedulerDate'] . ' ' . '06:00:00' ?? '';
        $soundTeamId = $postVars['who'] ?? '';
        $deviceId = $postVars['where'] ?? '';

        if(General::isNullOrEmpty($postVars['schedulerDate']) or General::isNullOrEmpty($postVars['who']) or General::isNullOrEmpty($postVars['where'])){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/new?status=failed');
        }


        $schedulerDate = new DateTime($schedulerDate, new DateTimeZone('America/Sao_Paulo'));
        $schedulerDate = $schedulerDate->setTimezone(new DateTimeZone('UTC'));

        // valida se já existe
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupByDeviceId($deviceId, $schedulerDate->format('d'), $schedulerDate->format('m'), $schedulerDate->format('Y'));
        if ($obSoundTeamLineup instanceof EntitySoundTeamLineup) {
            if($deviceId == 5 && $obSoundTeamLineup->sound_team_id == $soundTeamId){
                $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/new?status=duplicated');
            }elseif ($deviceId != 5){
                $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/new?status=duplicated');
            }
        }
        // Nova instancia
        $obSoundTeamLineup = new EntitySoundTeamLineup();
        $obSoundTeamLineup->scheduler_date = $schedulerDate->format('Y-m-d H:i:s');
        $obSoundTeamLineup->sound_team_id = $soundTeamId;
        $obSoundTeamLineup->sound_device_id = $deviceId;
        $obSoundTeamLineup->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/'.$obSoundTeamLineup->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditSoundTeamLineup(Request $request, int $id): string
    {
        // Obtém do banco de dados
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($id);

        // Valida instância
        if(!$obSoundTeamLineup instanceof EntitySoundTeamLineup){
            $request->getRouter()->redirect('/application/manager-sound-team');
        }

        $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
        $schedulerDate = $schedulerDate->format('Y-m-d');

        //Conteúdo do formulário
        $content = View::render('application/modules/manager-sound-team/forms/sound-team-lineup',[
            'title' => 'Editar Agenda Equipe',
            'breadcrumbItem' => 'Editar Agenda Equipe',
            'who' => self::getWho($obSoundTeamLineup->sound_team_id),
            'where' => self::getWhere($obSoundTeamLineup->sound_device_id),
            'schedulerDate' => $schedulerDate,
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de escala sonoplastia.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Cadastrar Agenda Equipe',$content,'manager-sound-team');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditSoundTeamLineup(Request $request, int $id): array
    {
        // Obtém do banco de dados
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($id);

        // Valida instância
        if(!$obSoundTeamLineup instanceof EntitySoundTeamLineup){
            $request->getRouter()->redirect('/application/manager-sound-team');
        }

        //PostVars
        $postVars = $request->getPostVars();

        $schedulerDate = $postVars['schedulerDate'] . ' ' . '06:00:00' ?? '';
        $soundTeamId = $postVars['who'] ?? '';
        $deviceId = $postVars['where'] ?? '';

        if(General::isNullOrEmpty($postVars['schedulerDate']) or General::isNullOrEmpty($postVars['who']) or General::isNullOrEmpty($postVars['where'])){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/new?status=failed');
        }

        $schedulerDate = new DateTime($schedulerDate, new DateTimeZone('America/Sao_Paulo'));
        $schedulerDate = $schedulerDate->setTimezone(new DateTimeZone('UTC'));

        // valida já existe
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupByDeviceId($deviceId, $schedulerDate->format('d'),$schedulerDate->format('m'), $schedulerDate->format('Y'));
        if($obSoundTeamLineup instanceof EntitySoundTeamLineup && $obSoundTeamLineup->id != $id){
            $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/new?status=duplicated');
        }

        //Debug::debug($schedulerDate->format('Y-m-d H:i:s'));

        // Nova instancia
        $obSoundTeamLineup = new EntitySoundTeamLineup();
        $obSoundTeamLineup->id = $id;
        $obSoundTeamLineup->scheduler_date = $schedulerDate->format('Y-m-d H:i:s');
        $obSoundTeamLineup->sound_team_id = $soundTeamId;
        $obSoundTeamLineup->sound_device_id = $deviceId;
        $obSoundTeamLineup->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team/sound-team-lineup/'.$obSoundTeamLineup->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteSoundTeamLineup(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($id);

        // Valida instância
        if(!$obSoundTeamLineup instanceof EntitySoundTeamLineup){
            $request->getRouter()->redirect('/application/manager-sound-team');
        }

        $content = View::render('application/modules/manager-sound-team/delete/sound-team-lineup', [
            'title' => 'Excluir Escala',
            'breadcrumbItem' => 'Excluir Escala',
            'who' => $obSoundTeamLineup->completed_name,
            'where' => $obSoundTeamLineup->device,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de escala sonoplastia.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Horários Sugeridos',$content,'manager-sound-team');

    }

    public static function setDeleteSoundTeamLineup(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obSoundTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupById($id);

        // Valida instância
        if(!$obSoundTeamLineup instanceof EntitySoundTeamLineup){
            $request->getRouter()->redirect('/application/manager-sound-team');
        }

        $obSoundTeamLineup->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/manager-sound-team?status=deleted');

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