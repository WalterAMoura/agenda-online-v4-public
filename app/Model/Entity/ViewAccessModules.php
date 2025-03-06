<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class ViewAccessModules
{

    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'cnt_access_modules_v2';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * Nome do menu
     * @var string
     */
    public $label;

    /**
     * Modulo do usuário
     * @var string
     */
    public $module;

    /**
     * Tipo Modulo do usuário
     * @var string
     */
    public $type_module;

    /**
     * Descrição nivel
     * @var string
     */
    public $description;

    /**
     * ID Modulo do usuário
     * @var integer
     */
    public $module_id;

    /**
     * ID Nivel do usuário
     * @var integer
     */
    public $level_id;

    /**
     * Nivel do usuário
     * @var integer
     */
    public $level;

    /**
     * @var string
     */
    public $home_path;

    /**
     * Data criação usuário
     * @var DateTime
     */
    public $created_at;

    /**
     * Data de atualização usuário
     * @var DateTime
     */
    public $updated_at;

    /**
     * @param int $id
     * @return ViewAccessModules
     */
    public static function getViewAccessModulesByLevelId(int $id)
    {
        return self::getViewAccessModules('level_id = '. $id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os modulos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getViewAccessModules($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_access_modules_v2'))->select($where,$order,$limit,$group,$fields);
    }
}