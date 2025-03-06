<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\DaysOfWeek as EntityDaysOfWeek;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\WorshipTeam as EntityWorshipTeam;
use App\Model\Entity\WorshipTeamLineup as EntityWorshipTeamLineup;
use App\Model\Entity\WorshipTeamLineupV2 as EntityWorshipTeamLineupV2;
use App\Model\Entity\Singers as EntitySingers;
use App\Model\Entity\SingersLineup as EntitySingersLineup;
use App\Model\Entity\AuxWorshipTeamSchedulerLineup as EntityAuxWorshipTeamSchedulerLineup;
use App\Model\Entity\AuxWorshipTeamScheduler as EntityAuxWorshipTeamScheduler;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\View;
use DateTime;
use DateTimeZone;
use Exception;

class WorshipTeamLineup extends Page
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
            $options .= View::render('application/modules/worship/forms/select',[
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
        $results = EntityWorshipTeam::getWorshipTeam($where, $order);
        while ($obWorshipTeam = $results->fetchObject(EntityWorshipTeam::class)){
            $options .= View::render('application/modules/worship/forms/select',[
                'optionValue' => $obWorshipTeam->id,
                'optionName' => $obWorshipTeam->complete_name,
                'selected' => ($obWorshipTeam->id === $selected)? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por lista a equipe
     * @param string|null $selected
     * @return string
     */
    private static function getSingers(string $selected = null): string
    {
        // carregar a equipe
        $options = '';

        $names = mb_split(',',$selected);
        $names_final = array();
        //remove os espaços em branco
        foreach ($names as $name){
            $names_final[] = trim($name);
        }

        $order = 'id ASC';
        $where = null;
        $results = EntitySingers::getSigers($where, $order);
        while ($obSingers = $results->fetchObject(EntitySingers::class)){
            $options .= View::render('application/modules/worship/forms/select2',[
                'optionValue' => $obSingers->id,
                'optionName' => $obSingers->singer,
                'selected' => (in_array($obSingers->singer, $names_final))? 'selected' : null
            ]);
        }
        return $options;
    }

    /**
     * Método responsável por lista a equipe
     * @param string|null $selected
     * @return string
     */
    private static function getWorships(string $selected = null): string
    {
        // carregar a equipe
        $options = '';

        $names = mb_split(',',$selected);
        $names_final = array();
        //remove os espaços em branco
        foreach ($names as $name){
            $names_final[] = trim($name);
        }

        $order = 'id ASC';
        $where = null;
        $results = EntityWorshipTeam::getWorshipTeam($where, $order);
        while ($obWorshipTeam = $results->fetchObject(EntityWorshipTeam::class)){
            $options .= View::render('application/modules/worship/forms/select2',[
                'optionValue' => $obWorshipTeam->id,
                'optionName' => $obWorshipTeam->complete_name,
                'selected' => (in_array($obWorshipTeam->complete_name, $names_final))? 'selected' : null
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
    public static function getNewWorshipTeamLineup(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/worship/forms/worship-team-lineup',[
            'title' => 'Cadastrar Agenda Equipe',
            'breadcrumbItem' => 'Cadastrar Agenda Equipe',
            'disabledNew' => 'disable',
            'displayNew' => 'none',
            'who' => self::getWho(),
            'optSingers' => self::getSingers(),
            'optWorships' => self::getWorships(),
            'worshipMusics' => null,
            'singerMusics' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de escala da recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Cadastrar Agenda Equipe',$content,'worship');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setWorshipTeamLineup(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();
        $schedulerDate = $postVars['schedulerDate'] . ' ' . '06:00:00' ?? '';
        $worshipsId = $postVars['worships'] ?? [];
        $worshipMusics = $postVars['worshipMusics'] ?? '';
        $singers = $postVars['singers'] ?? [];
        $singerMusics = $postVars['singerMusics'] ?? '';

        // valida campos obrigatórios
        if(General::isNullOrEmpty($postVars['schedulerDate']) or General::isArray($postVars['worships'])){
            $request->getRouter()->redirect('/application/worship/worship-team-lineup/new?status=failed');
        }

        // format schedulerDate
        $schedulerDate = new DateTime($schedulerDate, new DateTimeZone('America/Sao_Paulo'));
        $schedulerDate = $schedulerDate->setTimezone(new DateTimeZone('UTC'));

        // valida se já existe
        $obWorshipTeamLineup = EntityWorshipTeamLineup::getWorshipTeamLineupByScheduler($schedulerDate->format('d'),$schedulerDate->format('m'), $schedulerDate->format('Y'));
        if($obWorshipTeamLineup instanceof EntityWorshipTeamLineup){
            $request->getRouter()->redirect('/application/worship/worship-team-lineup/new?status=duplicated');
        }

        // 1º Insert tb_worship_team_schedule
        $obWorshipTeamLineup = new EntityWorshipTeamLineup();
        $obWorshipTeamLineup->scheduler_date = $schedulerDate->format('Y-m-d H:i:s');
        $obWorshipTeamLineup->cadastrar();

        if(!$obWorshipTeamLineup->id)
            $request->getRouter()->redirect('/application/worship/worship-team-lineup/new?status=failed');


        // 2° Insert tb_aux_worship_team_scheduler_lineup
        foreach ($worshipsId as $id){
            // Nova instancia
            $obAuxWorshipTeamLineup = new EntityAuxWorshipTeamSchedulerLineup();
            $obAuxWorshipTeamLineup->worship_team_scheduler_id = $obWorshipTeamLineup->id;
            $obAuxWorshipTeamLineup->worship_team_id = $id;
            $obAuxWorshipTeamLineup->cadastrar();
        }

        // 3° Insert tb_aux_worship_team_scheduler
        $obAuxWorshipTeam = new EntityAuxWorshipTeamScheduler();
        $obAuxWorshipTeam->worship_team_scheduler_id = $obWorshipTeamLineup->id;
        $obAuxWorshipTeam->worship_music = $worshipMusics;
        $obAuxWorshipTeam->singer_music = $singerMusics;
        $obAuxWorshipTeam->cadastrar();

        // 4° Insert tb_singer_scheduler
        foreach ($singers as $id){
            // Nova instancia
            if(!General::isNotNumeric($id)){
                $obSingersLineup = new EntitySingersLineup();
                $obSingersLineup->worship_team_scheduler_id = $obWorshipTeamLineup->id;
                $obSingersLineup->singer_id = $id;
                $obSingersLineup->cadastrar();
            }
        }

        // 5° Insert (cria singer id)
        foreach ($singers as $singer){
            if(General::isNotNumeric($singer)){
                $obSinger = new EntitySingers();
                $obSinger->singer = $singer;
                $obSinger->cadastrar();

                // inseri na escala de cantores
                $obSingersLineup = new EntitySingersLineup();
                $obSingersLineup->worship_team_scheduler_id = $obWorshipTeamLineup->id;
                $obSingersLineup->singer_id = $obSinger->id;
                $obSingersLineup->cadastrar();
            }
        }

        // Redireciona
        $request->getRouter()->redirect('/application/worship/worship-team-lineup/'.$obWorshipTeamLineup->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditWorshipTeamLineup(Request $request, int $id): string
    {
        // Obtém do banco de dados
        $obWorshipTeamLineup = EntityWorshipTeamLineupV2::getWorshipTeamLineupById($id);

        // Valida instância
        if(!$obWorshipTeamLineup instanceof EntityWorshipTeamLineupV2){
            $request->getRouter()->redirect('/application/worship/worship-team-lineup');
        }

        $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
        $schedulerDate = $schedulerDate->format('Y-m-d');

        //Conteúdo do formulário
        $content = View::render('application/modules/worship/forms/worship-team-lineup',[
            'title' => 'Editar Agenda Equipe',
            'breadcrumbItem' => 'Editar Agenda Equipe',
            'disabledNew' => null,
            'displayNew' => 'none',
            'optSingers' => self::getSingers($obWorshipTeamLineup->group_singer_names),
            'optWorships' => self::getWorships($obWorshipTeamLineup->group_complete_names),
            'worshipMusics' => $obWorshipTeamLineup->worship_music,
            'singerMusics' => $obWorshipTeamLineup->singer_music,
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
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de escala recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Cadastrar Agenda Equipe',$content,'worship');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditWorshipTeamLineup(Request $request, int $id): array
    {
        // Obtém do banco de dados
        $obWorshipTeamLineup = EntityWorshipTeamLineupV2::getWorshipTeamLineupById($id);

        // Valida instância
        if(!$obWorshipTeamLineup instanceof EntityWorshipTeamLineupV2){
            $request->getRouter()->redirect('/application/worship');
        }

        //PostVars
        $postVars = $request->getPostVars();
        $schedulerDate = $postVars['schedulerDate'] . ' ' . '06:00:00' ?? '';
        $worshipsId = $postVars['worships'] ?? [];
        $worshipMusics = $postVars['worshipMusics'] ?? '';
        $singers = $postVars['singers'] ?? [];
        $singerMusics = $postVars['singerMusics'] ?? '';

        // valida campos obrigatórios
        if(General::isNullOrEmpty($postVars['schedulerDate']) or General::isArray($postVars['worships'])){
            $request->getRouter()->redirect('/application/worship/worship-team-lineup/new?status=failed');
        }

        // formata a data
        $schedulerDate = new DateTime($schedulerDate, new DateTimeZone('America/Sao_Paulo'));
        $schedulerDate = $schedulerDate->setTimezone(new DateTimeZone('UTC'));

        /**
         * Primeiro precisa limpar os registros das tabelas, e depois inserir os novos
         */

        // tb_aux_worship_team_scheduler_lineup
        $aux = EntityAuxWorshipTeamSchedulerLineup::getAuxWorshipTeamLineup('worship_team_scheduler_id = '. $id);
        $obAuxWorshipTeamSchedulerLineup = new EntityAuxWorshipTeamSchedulerLineup();
        $obAuxWorshipTeamSchedulerLineup->id = $aux->fetchObject(EntityAuxWorshipTeamSchedulerLineup::class)->id;
        $obAuxWorshipTeamSchedulerLineup->excluir();

        //tb_singer_scheduler
        $result = EntitySingersLineup::getSingerLineup('worship_team_schedule_id = '. $id);
        while ($obj = $result->fetchObject(EntitySingersLineup::class)){
            $obSingersLineup = new EntitySingersLineup();
            $obSingersLineup->id = $obj->id;
            $obSingersLineup->excluir();
        }


        /**
         * Insere os novos registros nas tabelas
         */

        // 1º Insert tb_worship_team_schedule
        $obWorshipTeamLineup = new EntityWorshipTeamLineup();
        $obWorshipTeamLineup->id = $id;
        $obWorshipTeamLineup->scheduler_date = $schedulerDate->format('Y-m-d H:i:s');
        $obWorshipTeamLineup->atualizar();

        // tb_aux_worship_team_scheduler
        $aux = EntityAuxWorshipTeamScheduler::getAuxWorshipTeamLineup('worship_team_scheduler_id = '. $id);
        $obAuxWorshipTeamScheduler = new EntityAuxWorshipTeamScheduler();
        $obAuxWorshipTeamScheduler->id = $aux->fetchObject(EntityAuxWorshipTeamScheduler::class)->id;
        $obAuxWorshipTeamScheduler->worship_team_scheduler_id = $id;
        $obAuxWorshipTeamScheduler->worship_music = $worshipMusics;
        $obAuxWorshipTeamScheduler->singer_music = $singerMusics;
        $obAuxWorshipTeamScheduler->atualizar();

        // Insert tb_aux_worship_team_scheduler_lineup
        foreach ($worshipsId as $worshipId){
            // Nova instancia
            $obAuxWorshipTeamLineup = new EntityAuxWorshipTeamSchedulerLineup();
            $obAuxWorshipTeamLineup->worship_team_scheduler_id = $id;
            $obAuxWorshipTeamLineup->worship_team_id = $worshipId;
            $obAuxWorshipTeamLineup->cadastrar();
        }

        // Insert tb_singer_scheduler
        foreach ($singers as $singer){
            // Nova instancia
            if(!General::isNotNumeric($singer)){
                $obSingersLineup = new EntitySingersLineup();
                $obSingersLineup->worship_team_scheduler_id = $id;
                $obSingersLineup->singer_id = $singer;
                $obSingersLineup->cadastrar();
            }
        }

        //  Insert (cria singer id)
        foreach ($singers as $singer){
            if(General::isNotNumeric($singer)){
                $obSinger = new EntitySingers();
                $obSinger->singer = $singer;
                $obSinger->cadastrar();

                // inseri na escala de cantores
                $obSingersLineup = new EntitySingersLineup();
                $obSingersLineup->worship_team_scheduler_id = $id;
                $obSingersLineup->singer_id = $obSinger->id;
                $obSingersLineup->cadastrar();
            }
        }

        // Redireciona
        $request->getRouter()->redirect('/application/worship/worship-team-lineup/'.$obWorshipTeamLineup->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteWorshipTeamLineup(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obWorshipTeamLineup = EntityWorshipTeamLineupV2::getWorshipTeamLineupById($id);

        // Valida instância
        if(!$obWorshipTeamLineup instanceof EntityWorshipTeamLineupV2){
            $request->getRouter()->redirect('/application/worship');
        }

        $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
        $schedulerDate = $schedulerDate->format('Y-m-d');

        $content = View::render('application/modules/worship/delete/worship-team-lineup', [
            'title' => 'Excluir Escala',
            'breadcrumbItem' => 'Excluir Escala',
            'who' => $obWorshipTeamLineup->group_complete_names,
            'date' => $schedulerDate,
            'singers' => $obWorshipTeamLineup->group_singers,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de escala da recepção.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Horários Sugeridos',$content,'reception');

    }

    public static function setDeleteWorshipTeamLineup(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obWorshipTeamLineup = EntityWorshipTeamLineup::getWorshipTeamLineupById($id);

        // Valida instância
        if(!$obWorshipTeamLineup instanceof EntityWorshipTeamLineup){
            $request->getRouter()->redirect('/application/worship');
        }

        // tb_aux_worship_team_scheduler_lineup
        $aux = EntityAuxWorshipTeamSchedulerLineup::getAuxWorshipTeamLineup('worship_team_scheduler_id = '. $id);
        $obAuxWorshipTeamSchedulerLineup = new EntityAuxWorshipTeamSchedulerLineup();
        $obAuxWorshipTeamSchedulerLineup->id = $aux->fetchObject(EntityAuxWorshipTeamSchedulerLineup::class)->id;
        $obAuxWorshipTeamSchedulerLineup->excluir();

        //tb_singer_scheduler
        $result = EntitySingersLineup::getSingerLineup('worship_team_schedule_id = '. $id);
        while ($obj = $result->fetchObject(EntitySingersLineup::class)){
            $obSingersLineup = new EntitySingersLineup();
            $obSingersLineup->id = $obj->id;
            $obSingersLineup->excluir();
        }

        //tb_aux_worship_team_scheduler
        $aux =  EntityAuxWorshipTeamScheduler::getAuxWorshipTeamLineup('worship_team_scheduler_id = '. $id);
        $obAuxWorshipTeamScheduler = New EntityAuxWorshipTeamScheduler();
        $obAuxWorshipTeamScheduler->id = $aux->fetchObject(EntityAuxWorshipTeamScheduler::class)->id;
        $obAuxWorshipTeamScheduler->excluir();

        //tb_worship_team_schedule
        $obWorshipTeamLineup = new EntityWorshipTeamLineup();
        $obWorshipTeamLineup->id = $id;
        $obWorshipTeamLineup->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/worship?status=deleted');

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