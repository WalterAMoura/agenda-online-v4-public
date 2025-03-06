<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\Debug;
use App\Utils\View;
use App\Model\Entity\ElderMonthView as EntityElderMonthView;
use App\Model\Entity\ElderMonth as EntityElderMonth;
use App\Model\Entity\Years as EntityYears;
use App\Model\Entity\Elder as EntityElder;
use App\Model\Entity\Month as EntityMonth;
use App\Utils\General;
use DateTime;
use DateTimeZone;
use Exception;

class ElderMonth extends Page
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
                return Alert::getSuccess('Escala ancionato criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Escala atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Escala excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('Escala digitado já exite!');
                break;
            case 'failed':
                return Alert::getError('Ocorreu um erro ao atualizar ou inserir escala!');
                break;
            case 'rejected':
                return Alert::getWarning('Escala não pode ser apagado, porque já está em uso!');
                break;
        }
    }

    /**
     * Método responsável por lista os anciãos
     * @param string|null $selected
     * @return string
     */
    private static function getElders(string $selected = null)
    {
        // carregar anciãos
        $options = '';

        $names = mb_split(',',$selected);
        $names_final = array();
        //remove os espaços em branco
        foreach ($names as $name){
            $names_final[] = trim($name);
        }

        $order = 'id ASC';
        $results = EntityElder::getElders(null, $order);
        while ($obElder = $results->fetchObject(EntityElder::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obElder->id,
                'optionName' => $obElder->name,
                'selected' => (in_array($obElder->name, $names_final))? 'selected' : null
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por lista os meses
     * @param string|null $selected
     * @return string
     */
    private static function getMonths(string $selected = null)
    {
        // carregar meses
        $options = '';
//        echo $selected;
//        exit;
        $where = isset($selected)? 'id > 0' : null;
        $query = isset($selected)? 'id, long_description, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_elder' : '*';
        $order = isset($selected)? 'number_month ASC': null;
        $results = EntityMonth::getMonths($where,$order,null,null,$query);
        while ($obMonth = $results->fetchObject(EntityMonth::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obMonth->id,
                'optionName' => $obMonth->long_description,
                'selected' => ($selected == $obMonth->id)? 'selected' : null
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por lista os anos
     * @param string|null $selected
     * @return string
     * @throws Exception
     */
    private static function getYears(string $selected = null)
    {
        // carregar meses
        $options = '';

        $order = isset($selected)? 'order_elder' : null;
        $where = isset($selected)? 'id > 0' : null;
        $query = isset($selected)? 'id, year, CASE WHEN year = "'.$selected.'" THEN 0 ELSE 1 END AS order_elder' : '*';

        $results = EntityYears::getYears($where,$order,null,null,$query);
        while ($obYear = $results->fetchObject(EntityYears::class)){
            $options .= View::render('application/modules/config-event/forms/select',[
                'optionValue' => $obYear->id,
                'optionName' => $obYear->year,
                'selected' => ($selected == $obYear->id)? 'selected' : null
            ]);
        }

        return $options;
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo departamento ou ministério
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewElderMonth(Request $request): string
    {
        // recupera ano corrente
        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/elder-month',[
            'title' => 'Cadastrar Anciãos do Mês',
            'breadcrumbItem' => 'Cadastrar Anciãos do Mês',
            'optElderMonth' => self::getElders(),
            'optMonths' => self::getMonths(),
            'optYears' => self::getYears($date->format('Y')),
            'disabled' => null,
            'btnTipo' => 'primary',
            'btnNome' => 'Incluir',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de ancião do mês.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewElderMonth(Request $request): array
    {
        //PostVars
        $postVars = $request->getPostVars();
        $eldersMonth = $postVars['eldersMonth'];
        $month = (int)$postVars['month'];
        $year = (int)$postVars['year'];

        // valida se os campos estão vazios
        if(General::isArray($postVars['eldersMonth']) or General::isNullOrEmpty($postVars['month']) or General::isNullOrEmpty($postVars['year'])){
            $request->getRouter()->redirect('/application/config-event/elder-month/new?status=failed');
        }

        // valida escala do mes e ano
        $obElderMonth = EntityElderMonth::getElderMonthByMonth($month, $year);
        if($obElderMonth instanceof EntityElderMonth){
            $request->getRouter()->redirect('/application/config-event/elder-month/new?status=duplicated');
        }

        // intera em cada ancião recebido para realizar o insert
        foreach ($eldersMonth as $elder){
            $obElderMonth = new EntityElderMonth();
            $obElderMonth->elder_id = $elder;
            $obElderMonth->year_id = $year;
            $obElderMonth->month_id = $month;
            $obElderMonth->cadastrar();
        }

        $request->getRouter()->redirect('/application/config-event/elder-month/'.$obElderMonth->month_id.'/'.$obElderMonth->year_id.'/edit?status=created');

        // retorno de sucesso
        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param int $month
     * @param int $year
     * @return string
     * @throws Exception
     */
    public static function getEditElderMonth(Request $request, int $month, int $year): string
    {
        // valida escala do mes e ano

        $obElderMonth = EntityElderMonthView::getElderMonthByMonthV2($month, $year);
        if(!$obElderMonth instanceof EntityElderMonthView){
            $request->getRouter()->redirect('/application/config-event/elder-month?status=deleted');
        }

        // recupera ano corrente
        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/forms/elder-month',[
            'title' => 'Cadastrar Anciãos do Mês',
            'breadcrumbItem' => 'Cadastrar Anciãos do Mês',
            'optElderMonth' => self::getElders($obElderMonth->name),
            'optMonths' => self::getMonths($month),
            'optYears' => self::getYears($date->format($year)),
            'disabled' => 'disabled',
            'btnTipo' => 'success',
            'btnNome' => 'Atualizar',
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de ancião do mês.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param int $month
     * @param int $year
     * @return array
     * @throws Exception
     */
    public static function setEditElderMonth(Request $request, int $month, int $year): array
    {
        // valida escala do mes e ano
        $obElderMonth = EntityElderMonthView::getElderMonthByMonthV2($month, $year);
        if(!$obElderMonth instanceof EntityElderMonthView){
            $request->getRouter()->redirect('/application/config-event/elder-month?status=deleted');
        }

        //PostVars
        $postVars = $request->getPostVars();
        $eldersMonth = $postVars['eldersMonth'];

        // valida se os campos estão vazios
        if(General::isArray($postVars['eldersMonth'])){
            $request->getRouter()->redirect('/application/config-event/elder-month/'.$month.'/'.$year.'/edit?status=failed');
        }

        // exclui os registros
        $results = EntityElderMonth::getElderMonth('month_id = '. $month . ' AND year_id = '. $year);

        while($objt = $results->fetchObject(EntityElderMonth::class)){
            $obElderMonth = new EntityElderMonth();
            $obElderMonth->id = $objt->id;
            $obElderMonth->excluir();
        }

        // intera em cada ancião recebido para realizar o insert
        foreach ($eldersMonth as $elder){
            $obElderMonth = new EntityElderMonth();
            $obElderMonth->elder_id = $elder;
            $obElderMonth->year_id = $year;
            $obElderMonth->month_id = $month;
            $obElderMonth->cadastrar();
        }

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder-month/'.$month.'/'.$year.'/edit?status=updated');

        return [ "success" => true];
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um departamento
     * @param Request $request
     * @param int $month
     * @param int $year
     * @return string
     * @throws Exception
     */
    public static function getDeleteElderMonth(Request $request, int $month, int $year): string
    {
        // valida escala do mes e ano
        $obElderMonthView = EntityElderMonthView::getElderMonthByMonthV2($month, $year);


        if(!$obElderMonthView instanceof EntityElderMonthView){
            $request->getRouter()->redirect('/application/config-event/elder-month?status=deleted');
        }


        //Conteúdo do formulário
        $content = View::render('application/modules/config-event/delete/elder-month',[
            'title' => 'Excluir Escala',
            'breadcrumbItem' => 'Excluir Escala',
            'elders' => $obElderMonthView->name,
            'month' => $obElderMonthView->month_long_description,
            'year' => $obElderMonthView->year,
            'status' => self::getStatus($request)
        ]);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de ancião do mês.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Configurações',$content,'config-event');
    }

    /**
     * Método responsável por excluir de um departamento
     * @param Request $request
     * @param int $month
     * @param int $year
     * @return array
     */
    public static function setDeleteElderMonth(Request $request, int $month, int $year): array
    {
        // valida escala do mes e ano
        $obElderMonthView = EntityElderMonthView::getElderMonthByMonthV2($month, $year);
        if(!$obElderMonthView instanceof EntityElderMonthView){
            $request->getRouter()->redirect('/application/config-event/elder-month?status=failed');
        }

        // exclui os registros
        $results = EntityElderMonth::getElderMonth('month_id = '. $month . ' AND year_id = '. $year);
        while($objt = $results->fetchObject(EntityElderMonth::class)){
            $obElderMonth = new EntityElderMonth();
            $obElderMonth->id = $objt->id;
            $obElderMonth->excluir();
        }

        // Redireciona
        $request->getRouter()->redirect('/application/config-event/elder-month?status=deleted');

        return [ "success" => true];

    }
}