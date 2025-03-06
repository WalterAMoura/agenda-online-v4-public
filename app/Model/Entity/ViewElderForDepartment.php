<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class ViewElderForDepartment
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'cnt_elder_for_department_v2';

    /**
     * Ids do departamento
     * @var string
     */
    public $department_ids;

    /**
     * Id do ancião responsável pelo departamento
     * @var integer
     */
    public $elder_id;

    /**
     * Nome completo do ancião responsável
     * @var string
     */
    public $elder_complete_name;

    /**
     * Nome do ancião responsável
     * @var string
     */
    public $elder_name;

    /**
     * Contato do ancião responsável
     * @var string
     */
    public $elder_phone;

    /**
     * Contato do ancião responsável
     * @var string
     */
    public $elder_phone_mask;

    /**
     * Método responsável por retornar todos os departamentos por ancião
     * @param integer $elderId
     * @return ViewElderForDepartment
     */
    public static function getElderForDepartmentsByElder(int $elderId)
    {
        return self::getViewElderForDepartment('elder_id = ' . $elderId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getViewElderForDepartment($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_elder_for_department_v2'))->select($where,$order,$limit,$group,$fields);
    }
}