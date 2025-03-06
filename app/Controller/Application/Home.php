<?php

namespace App\Controller\Application;

use App\Http\Request;
use App\Model\Entity\Elder as EntityElder;
use App\Model\Entity\ElderForDepartment as EntityElderForDepartment;
use App\Model\Entity\ViewElderForDepartment as EntityViewElderForDepartment;
use App\Model\Entity\ElderMonthView as EntityElderMonthView;
use App\Model\Entity\Event as EntityEvent;
use App\Model\Entity\EventsChurch as EntityEventsChurch;
use App\Model\Entity\Organization as EntityOrganization;
use App\Session\Users\Login as SessionUsersLogin;
use App\Utils\Debug;
use App\Utils\View;
use App\Utils\ViewJS;
use DateTime;
use DateTimeZone;
use Exception;

class Home extends Page
{
    /**
     * @var array|array[]
     */
    private static array $tables = [
        'elderMonth' => [
            'path' => 'application/modules/home/tables',
            'name' => 'elder-month'
        ],
        'myAgenda' => [
            'path' => 'application/modules/home/tables',
            'name' => 'my-agenda'
        ],
        'departments' => [
            'path' => 'application/modules/home/tables',
            'name' => 'departments'
        ],
        'agendaDepartments' => [
            'path' => 'application/modules/home/tables',
            'name' => 'agenda-departments'
        ],
        'eventosDepartments' => [
            'path' => 'application/modules/home/tables',
            'name' => 'eventos-departments'
        ],
        'agendaPastoral' => [
            'path' => 'application/modules/home/tables',
            'name' => 'agenda-pastoral'
        ]
    ];

    /**
     * Método responsável por retornar os scripts js para gráficos
     * @return string
     * @throws Exception
     */
    private static function getTables(): string
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $userId = $session['usuario']['id'];

        //scripts
        $tables = '';

        //Navega nos gráficos
        foreach (self::$tables as $module) {
            //$tables .= Charts::getCharts($module['type'],$module['script'],$module['id']);
            $tables .= View::render($module['path'] . '/' . $module['name'],[
                'items' => self::getItemsTables($module['name'],$userId)
            ]);
        }

        return $tables;
    }

    /**
     * @param string $module
     * @param int $userId
     * @return string|null
     * @throws Exception
     */
    private static function getItemsTables(string $module, int $userId)
    {
        return match ($module) {
            'elder-month' => self::getElderMonthItems($module, $userId),
            'my-agenda' => self::getEventItems($module, $userId),
            'departments' => self::getElderForDeparmentItems($module, $userId),
            'agenda-pastoral' => self::getAgendaPastoralItems($module, 37),
            'agenda-departments' => self::getEventsDepartentItems($module, $userId),
            'eventos-departments' => self::getEventsChurchDepartentItems($module, $userId),
            default => null,
        };
    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @return string
     * @throws Exception
     * @returm string
     */
    private static function getElderMonthItems(string $module, int $userId)
    {
        // recupera o nome do ancião por user id
        $obElder = EntityElder::getElderByUserId($userId);

        if(!$obElder instanceof EntityElder){
            return '';
        }

        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $date->format('Y');
        $yearOld = $year -1;
        $listYears = array($year,$yearOld);
        $years = implode(',', $listYears);

        // usuários
        $itens = '';
        $query = 'name LIKE "%' . $obElder->name . '%" AND year IN (' . $years . ')';
        // instancia do banco de dados
        $results = EntityElderMonthView::getElderMonth($query,'year DESC, month ASC');
        $i=1;
        // renderiza item
        while ($obElderMonthView = $results->fetchObject(EntityElderMonthView::class)){
            $itens .= View::render('application/modules/home/items/'. $module, [
                'id' => $i,
                'names' => $obElderMonthView->name,
                'month' => $obElderMonthView->month_long_description,
                'year' => $obElderMonthView->year
            ]);
            $i++;
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @param int $userId
     * @return string
     * @returm string
     * @throws Exception
     */
    private static function getEventItems(string $module, int $userId)
    {
        // recupera o nome do ancião por user id
        $obElder = EntityElder::getElderByUserId($userId);

        if(!$obElder instanceof EntityElder){
            return '';
        }

        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $date->format('Y');
        $yearOld = $year -1;
        $listYears = array($year,$yearOld);
        $years = implode(',', $listYears);
        $order = '`original_start` ASC';


        // usuários
        $itens = '';
        $query = 'orador LIKE "%' . $obElder->complete_name . '%" AND year_start IN (' . $years . ')';

        $results = EntityEvent::getEvents($query,$order,null,null,'*, DATE_FORMAT(start, "%d-%m-%Y") as start, CASE WHEN `month_start` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Renderiza o item
        while ($obEvent = $results->fetchObject(EntityEvent::class)){
            $itens .= View::render('application/modules/home/items/'. $module,[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'diaDaSemana' => $obEvent->day_of_week_long_description,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'hinoInicial' => $obEvent->hino_inicial,
                'hinoFinal' => $obEvent->hino_final,
                'orador' => $obEvent->orador,
                'departamento' => $obEvent->department,
                'programacao' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'month' => $obEvent->month_long_description,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'applicationdo.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'urlImg' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @return string
     * @throws Exception
     * @returm string
     */
    private static function getElderForDeparmentItems(string $module, int $userId)
    {
        // recupera o nome do ancião por user id
        $obElder = EntityElder::getElderByUserId($userId);

        if(!$obElder instanceof EntityElder){
            return '';
        }

        // usuários
        $itens = '';
        $query = 'elder_id = ' . $obElder->id;

        // instancia do banco de dados
        $results = EntityElderForDepartment::getElderForDepartment($query);
        // renderiza item
        while ($obElderForDepartment = $results->fetchObject(EntityElderForDepartment::class)){
            $itens .= View::render('application/modules/home/items/'. $module, [
                'id' => $obElderForDepartment->id,
                'department' => $obElderForDepartment->department_name,
                'departmentDirector' => $obElderForDepartment->department_director,
                'departmentDirectorPhone' => $obElderForDepartment->director_phone_number_mask
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @param int $userId
     * @return string
     * @returm string
     * @throws Exception
     */
    private static function getAgendaPastoralItems(string $module, int $userId)
    {
        // recupera o nome do ancião por user id
        $obElder = EntityElder::getElderByUserId($userId);

        if(!$obElder instanceof EntityElder){
            return '';
        }

        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $date->format('Y');
        $yearOld = $year -1;
        $listYears = array($year,$yearOld);
        $years = implode(',', $listYears);
        $order = '`year_start` DESC, `original_start` ASC, `order_month` ASC';

        // usuários
        $itens = '';
        $query = 'orador LIKE "%' . $obElder->complete_name . '%" AND year_start in (' . $years . ')';

        $results = EntityEvent::getEvents($query,$order,null,null,'*, DATE_FORMAT(start, "%d-%m-%Y") as start, CASE WHEN `month_start` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Renderiza o item
        while ($obEvent = $results->fetchObject(EntityEvent::class)){
            $itens .= View::render('application/modules/home/items/'. $module,[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'diaDaSemana' => $obEvent->day_of_week_long_description,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'hinoInicial' => $obEvent->hino_inicial,
                'hinoFinal' => $obEvent->hino_final,
                'orador' => $obEvent->orador,
                'departamento' => $obEvent->department,
                'programacao' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'month' => $obEvent->month_long_description,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'applicationdo.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'urlImg' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @param int $userId
     * @return string
     * @returm string
     * @throws Exception
     */
    private static function getEventsDepartentItems(string $module, int $userId)
    {
        // recupera o nome do ancião por user id
        $obElder = EntityElder::getElderByUserId($userId);

        if(!$obElder instanceof EntityElder){
            return '';
        }

        // recupera departmentos
        $obViewElderForDepartment = EntityViewElderForDepartment::getElderForDepartmentsByElder($obElder->id);

        if(!$obViewElderForDepartment instanceof EntityViewElderForDepartment){
            return '';
        }

        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $date->format('Y');
        $yearOld = $year -1;
        $listYears = array($year,$yearOld);
        $years = implode(',', $listYears);
        $order = '`year_start` DESC, `original_start` ASC, `order_month` ASC';
        $departments = $obViewElderForDepartment->department_ids ;

        // usuários
        $itens = '';
        $query = 'department_id IN (' . $departments .') AND year_start IN (' . $years . ')';
        $results = EntityEvent::getEvents($query,$order,null,null,'*, DATE_FORMAT(start, "%d-%m-%Y") as start, CASE WHEN `month_start` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Renderiza o item
        while ($obEvent = $results->fetchObject(EntityEvent::class)){
            $itens .= View::render('application/modules/home/items/'. $module,[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'diaDaSemana' => $obEvent->day_of_week_long_description,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'hinoInicial' => $obEvent->hino_inicial,
                'hinoFinal' => $obEvent->hino_final,
                'orador' => $obEvent->orador,
                'departamento' => $obEvent->department,
                'programacao' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'month' => $obEvent->month_long_description,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'applicationdo.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'urlImg' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param string $module
     * @param int $userId
     * @return string
     * @returm string
     * @throws Exception
     */
    private static function getEventsChurchDepartentItems(string $module, int $userId)
    {
        // recupera o nome do ancião por user id
        $obElder = EntityElder::getElderByUserId($userId);

        if(!$obElder instanceof EntityElder){
            return '';
        }

        // recupera departmentos
        $obViewElderForDepartment = EntityViewElderForDepartment::getElderForDepartmentsByElder($obElder->id);
//        if(!$obViewElderForDepartment instanceof EntityViewElderForDepartment){
//            return '';
//        }
        $departments = (!$obViewElderForDepartment instanceof EntityViewElderForDepartment)? '22': $obViewElderForDepartment->department_ids . ', 22';

        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $year = $date->format('Y');
        $yearOld = $year -1;
        $listYears = array($year,$yearOld);
        $years = implode(',', $listYears);
        $order = '`year_start` DESC, `original_start` ASC, `order_month` ASC';
        //$departments = $obViewElderForDepartment->department_ids;

        // usuários
        $itens = '';
        $query = 'department_id IN (' . $departments .') AND year_start IN (' . $years . ')';
        $results = EntityEventsChurch::getEvents($query,$order,null,null,'*, DATE_FORMAT(start, "%d-%m-%Y") as start, CASE WHEN `month_start` = DATE_FORMAT(CURRENT_TIMESTAMP, "%m") THEN 0 ELSE 1 END AS order_month');

        //Renderiza o item
        while ($obEvent = $results->fetchObject(EntityEventsChurch::class)){
            $itens .= View::render('application/modules/home/items/'. $module,[
                'id' => $obEvent->id,
                'data' => $obEvent->start,
                'diaDaSemana' => $obEvent->day_of_week_long_description,
                'contato' => $obEvent->phone_mask,
                'tema' => $obEvent->description,
                'hinoInicial' => $obEvent->hino_inicial,
                'hinoFinal' => $obEvent->hino_final,
                'owner' => $obEvent->owner,
                'departamento' => $obEvent->department,
                'programacao' => $obEvent->program,
                'statusEvento' => $obEvent->description_status,
                'month' => $obEvent->month_long_description,
                //'imgStatus' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? 'pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? 'applicationdo.png' : 'confirmado.png'),
                'imgStatus' => mb_strtolower($obEvent->status) . '.png',
                //'url' => ($obEvent->status == 'PENDENTE_CONFIRMAR') ? URL .'/lib/img/pendente_confirmar.png' : (($obEvent->status == 'AGENDADO') ? URL .'/lib/img/applicationdo.png' : URL .'/lib/img/confirmado.png')
                'urlImg' => URL .'/lib/img/circule-'. mb_strtolower($obEvent->color) .'.png'
            ]);
        }

        // retorna os depoimentos
        return $itens;

    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string|null
     */
    private static function getStatus(Request $request): string|null
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if (!isset($queryParams['status'])) return null;

        //Mensagens de status
        return match ($queryParams['status']) {
            'updated-pwd', 'updated' => Alert::getSuccess('Senha atualizada com sucesso!'),
            'failed' => Alert::getError('Ocorreu um erro ao atualizar senha!'),
            default => null,
        };
    }

    /**
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getHome(Request $request): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null, 'created_at DESC', 1)->fetchObject();

        //Conteúdo da home
        $content = View::render('application/modules/home/index', [
            'status' => self::getStatus($request),
            'tables' => self::getTables()
        ]);

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Home', $content, 'home');
    }
}