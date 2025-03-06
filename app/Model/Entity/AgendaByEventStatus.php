<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class AgendaByEventStatus
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
     * Status Evento
     * @var string
     */
    public string $status;

    /**
     * Cor do status
     * @var string
     */
    public string $color;

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
    public static function getAgendaByEventStatus($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_agenda_by_status'))->select($where,$order,$limit,$group,$fields);
    }
}