<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class AgendaByPrograms
{
    /**
     * Ano da agenda
     * @var string|integer
     */
    public string|int $year;


    /**
     * id Deparamento
     * @var string|integer
     */
    public string|int $id;

    /**
     * Deparamento ou ministério
     * @var string
     */
    public string $description;

    /**
     * id Deparamento
     * @var string|integer
     */
    public string|int $total;

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getAgendaByPrograms($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_agenda_by_program'))->select($where,$order,$limit,$group,$fields);
    }
}