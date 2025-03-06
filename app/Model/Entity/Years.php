<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class Years
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_years';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Ano
     * @var string
     */
    public $year;

    /**
     * Método responsável por retornar um ano pelo id
     * @param string|integer $year
     * @return Month
     */
    public static function getYearById($year)
    {
        return self::getYears('year = "'.$year.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getYears($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_years'))->select($where,$order,$limit,$group,$fields);
    }
}