<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class Month
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_month';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Número do mês
     * @var integer
     */
    public $number_month;

    /**
     * Descrição curta do mês
     * @var string
     */
    public $short_description;

    /**
     * Descrição larga do mês
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
     * Método responsável por retornar um mês pelo número
     * @param integer $monthNumber
     * @return Month
     */
    public static function getMonthById($monthNumber)
    {
        return self::getMonths('month_number = '.$monthNumber)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getMonths($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_month'))->select($where,$order,$limit,$group,$fields);
    }
}