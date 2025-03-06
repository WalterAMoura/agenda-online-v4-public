<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class ElderMonthView
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'cnt_elder_month_v2';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Id do ancião
     * @var integer
     */
    public $elder_id;

    /**
     * Nome do ancião
     * @var string
     */
    public $name;

    /**
     * Id do mês
     * @var integer
     */
    public $month_id;

    /**
     * Número do mês
     * @var integer
     */
    public $month;

    /**
     * Descrição curta do mês
     * @var string
     */
    public $month_short_description;

    /**
     * Descrição larga do mês
     * @var string
     */
    public $month_long_description;

    /**
     * ID do ano
     * @var integer
     */
    public $year_id;

    /**
     * Ano
     * @var string|integer
     */
    public $year;

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
     * Método responsável por retonar um departamento pelo ID
     * @param integer $id
     * @return Elder
     */
    public static function getElderMonthById($id)
    {
        return self::getElderMonth('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um departamento pelo nome
     * @param integer $month
     * @param integer $year
     * @return Elder
     */
    public static function getElderMonthByMonth($month, $year)
    {
        return self::getElderMonth('month = '.$month . ' AND year ="'.$year.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um departamento pelo nome
     * @param integer $month
     * @param integer $year
     * @return Elder
     */
    public static function getElderMonthByMonthV2($month, $year)
    {
        return self::getElderMonth('month_id = '.$month . ' AND year_id ='.$year)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getElderMonth($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_elder_month_v2'))->select($where,$order,$limit,$group,$fields);
    }
}