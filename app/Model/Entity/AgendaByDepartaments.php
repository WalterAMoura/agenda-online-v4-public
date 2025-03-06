<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class AgendaByDepartaments
{
    /**
     * Ano da agenda
     * @var string|integer
     */
    public $year;


    /**
     * id Deparamento
     * @var string|integer
     */
    public $id;

    /**
     * Deparamento ou ministério
     * @var string
     */
    public $department;

    /**
     * id Deparamento
     * @var string|integer
     */
    public $total;

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getAgendaByDepartments($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_agenda_by_department'))->select($where,$order,$limit,$group,$fields);
    }
}