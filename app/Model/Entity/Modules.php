<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class Modules
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_modules';
    /**
     * ‘ID’ do nosso módulo
     * @var integer
     */
    public $id;

    /**
     * tipo modulo
     * @var integer
     */
    public $type_id;

    /**
     * tipo modulo
     * @var string
     */
    public $type;

    /**
     * Modulo do usuário
     * @var string
     */
    public $module;

    /**
     * Nome do menu
     * @var string
     */
    public $label;

    /**
     * Ícone do menu
     * @var string|null
     */
    public $icon;

    /**
     * Path Modulo do usuário
     * @var string
     */
    public $path_module;

    /**
     * @var string|boolean|null|integer
     */
    public $current;

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
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $dtCriacao = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCriacao->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'module' => $this->module,
            'label' => $this->label,
            'icon' => $this->icon,
            'path_module' => $this->path_module,
            'type_id' => $this->type_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ]);

        // Success
        return true;
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function atualizar()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'module' => $this->module,
            'label' => $this->label,
            'icon' => $this->icon,
            'path_module' => $this->path_module,
            'type_id' => $this->type_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluir um level do banco de dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retornar um módulo pelo ID
     * @param integer $id
     * @return Modules
     */
    public static function getModuleById(int $id)
    {
        return self::getModules('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um módulo pelo nome
     * @param string $name
     * @return Modules
     */
    public static function getModuleByName(string $name)
    {
        return self::getModules('module = "'.$name.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um módulo pelo nome
     * @param string $name
     * @param int $typeModuleId
     * @return Modules
     */
    public static function getModuleByNameAndByType(string $name, int $typeModuleId)
    {
        return self::getModules('module = "'.$name.'" AND type_id = '.$typeModuleId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um módulo pelo tipo module id
     * @param int $typeModuleId
     * @return Modules
     */
    public static function getModuleByTypeModuleId(int $typeModuleId)
    {
        return self::getModules('type_id = '.$typeModuleId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os módulos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getModules($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_modules'))->select($where,$order,$limit,$group,$fields);
    }
}