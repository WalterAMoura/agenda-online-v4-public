<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\DaysOfWeek as EntityDaysOfWeek;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\ReceptionTeam as EntityReceptionTeam;
use App\Model\Entity\ReceptionTeamLineup as EntityReceptionTeamLineup;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\View;
use DateTime;
use DateTimeZone;
use Exception;

class ReceptionTeamLineup extends Page
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
            $options .= View::render('application/modules/reception/forms/select',[
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
        $results = EntityReceptionTeam::getReceptionTeam($where, $order);
        while ($obReceptionTeam = $results->fetchObject(EntityReceptionTeam::class)){
            $options .= View::render('application/modules/reception/forms/select',[
                'optionValue' => $obReceptionTeam->id,
                'optionName' => $obReceptionTeam->complete_name,
                'selected' => ($obReceptionTeam->id === $selected)? 'selected' : null
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
    public static function getNewReceptionTeamLineup(Request $request): string
    {
        //Conteúdo do formulário
        $content = View::render('application/modules/reception/forms/reception-team-lineup',[
            'title' => 'Cadastrar Agenda Equipe',
            'breadcrumbItem' => 'Cadastrar Agenda Equipe',
            'disabledNew' => 'disable',
            'displayNew' => 'none',
            'who' => self::getWho(),
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
        return parent::getPanel($obOrganization->full_name . ' | Cadastrar Agenda Equipe',$content,'reception');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setReceptionTeamLineup(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();

        $schedulerDate = $postVars['schedulerDate'] . ' ' . '06:00:00' ?? '';
        $receptionTeamId = $postVars['who'] ?? '';

        if(General::isNullOrEmpty($postVars['schedulerDate']) or General::isNullOrEmpty($postVars['who'])){
            $request->getRouter()->redirect('/application/reception/reception-team-lineup/new?status=failed');
        }


        $schedulerDate = new DateTime($schedulerDate, new DateTimeZone('America/Sao_Paulo'));
        $schedulerDate = $schedulerDate->setTimezone(new DateTimeZone('UTC'));

        // valida se já existe
//        $obReceptionTeamLineup = EntityReceptionTeamLineup::getReceptionTeamLineup($schedulerDate->format('d'),$schedulerDate->format('m'), $schedulerDate->format('Y'));
//        if($obReceptionTeamLineup instanceof EntitySoundTeamLineup){
//            $request->getRouter()->redirect('/application/reception/reception-team-lineup/new?status=duplicated');
//        }

        // Nova instancia
        $obReceptionTeamLineup = new EntityReceptionTeamLineup();
        $obReceptionTeamLineup->scheduler_date = $schedulerDate->format('Y-m-d H:i:s');
        $obReceptionTeamLineup->reception_team_id = $receptionTeamId;
        $obReceptionTeamLineup->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/reception/reception-team-lineup/'.$obReceptionTeamLineup->id.'/edit?status=created');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditReceptionTeamLineup(Request $request, int $id): string
    {
        // Obtém do banco de dados
        $obReceptionTeamLineup = EntityReceptionTeamLineup::getReceptionTeamLineupById($id);

        // Valida instância
        if(!$obReceptionTeamLineup instanceof EntityReceptionTeamLineup){
            $request->getRouter()->redirect('/application/reception-team-lineup');
        }

        $schedulerDate = new DateTime($obReceptionTeamLineup->scheduler_date);
        $schedulerDate = $schedulerDate->format('Y-m-d');

        //Conteúdo do formulário
        $content = View::render('application/modules/reception/forms/reception-team-lineup',[
            'title' => 'Editar Agenda Equipe',
            'breadcrumbItem' => 'Editar Agenda Equipe',
            'disabledNew' => null,
            'displayNew' => 'block',
            'who' => self::getWho($obReceptionTeamLineup->reception_team_id),
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
        return parent::getPanel($obOrganization->full_name . ' | Cadastrar Agenda Equipe',$content,'reception');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return true[]
     * @throws Exception
     */
    public static function setEditReceptionTeamLineup(Request $request, int $id): array
    {
        // Obtém do banco de dados
        $obReceptionTeamLineup = EntityReceptionTeamLineup::getReceptionTeamLineupById($id);

        // Valida instância
        if(!$obReceptionTeamLineup instanceof EntityReceptionTeamLineup){
            $request->getRouter()->redirect('/application/reception');
        }
        //Debug::debug($obReceptionTeamLineup);

        //PostVars
        $postVars = $request->getPostVars();

        $schedulerDate = $postVars['schedulerDate'] . ' ' . '06:00:00' ?? '';
        $receptionTeamId = $postVars['who'] ?? '';

        if(General::isNullOrEmpty($postVars['schedulerDate']) or General::isNullOrEmpty($postVars['who'])){
            $request->getRouter()->redirect('/application/reception/reception-team-lineup/new?status=failed');
        }

        $schedulerDate = new DateTime($schedulerDate, new DateTimeZone('America/Sao_Paulo'));
        $schedulerDate = $schedulerDate->setTimezone(new DateTimeZone('UTC'));

        // valida já existe
//        $obReceptionTeamLineup = EntitySoundTeamLineup::getSoundTeamLineupByDeviceId($deviceId, $schedulerDate->format('d'),$schedulerDate->format('m'), $schedulerDate->format('Y'));
//        if($obReceptionTeamLineup instanceof EntitySoundTeamLineup && $obReceptionTeamLineup->id != $id){
//            $request->getRouter()->redirect('/application/reception/reception-team-lineup/new?status=duplicated');
//        }

        //Debug::debug($schedulerDate->format('Y-m-d H:i:s'));

        // Nova instancia
        $obReceptionTeamLineup = new EntityReceptionTeamLineup();
        $obReceptionTeamLineup->id = $id;
        $obReceptionTeamLineup->scheduler_date = $schedulerDate->format('Y-m-d H:i:s');
        $obReceptionTeamLineup->reception_team_id = $receptionTeamId;
        $obReceptionTeamLineup->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/reception/reception-team-lineup/'.$obReceptionTeamLineup->id.'/edit?status=updated');

        return [ "success" => true];

    }

    /**
     * @param Request $request
     * @param int $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteReceptionTeamLineup(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obReceptionTeamLineup = EntityReceptionTeamLineup::getReceptionTeamLineupById($id);

        // Valida instância
        if(!$obReceptionTeamLineup instanceof EntityReceptionTeamLineup){
            $request->getRouter()->redirect('/application/reception');
        }

        $content = View::render('application/modules/reception/delete/reception-team-lineup', [
            'title' => 'Excluir Escala',
            'breadcrumbItem' => 'Excluir Escala',
            'who' => $obReceptionTeamLineup->completed_name,
            'date' => $obReceptionTeamLineup->scheduler_date,
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

    public static function setDeleteReceptionTeamLineup(Request $request, int $id)
    {
        // Obtém do banco de dados
        $obReceptionTeamLineup = EntityReceptionTeamLineup::getReceptionTeamLineupById($id);

        // Valida instância
        if(!$obReceptionTeamLineup instanceof EntityReceptionTeamLineup){
            $request->getRouter()->redirect('/application/reception');
        }

        $obReceptionTeamLineup->excluir();

        // Redireciona
        $request->getRouter()->redirect('/application/reception?status=deleted');

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