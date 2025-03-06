<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class DaysOfWeek
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_days_of_week';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Número do dia
     * @var integer
     */
    public $number_day;

    /**
     * Descrição curta do dia
     * @var string
     */
    public $short_description;

    /**
     * Descrição larga do dia
     * @var string
     */
    public $long_description;

    /**
     * Data criação
     * @var DateTime|string
     */
    public $created_at;

    /**
     * Data de atualização
     * @var DateTime|string
     */
    public $updated_at;

    /**
     * Método responsável por retornar o dia da semana pelo id
     * @param int $id
     * @return Month
     */
    public static function getDayOfWeekById(int $id)
    {
        return self::getDaysOfWeek('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getDaysOfWeek($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_days_of_week'))->select($where,$order,$limit,$group,$fields);
    }
}