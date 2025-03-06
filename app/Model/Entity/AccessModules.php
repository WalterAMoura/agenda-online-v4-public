<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class AccessModules
{

    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_access_modules';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * @var string|boolean|int
     */
    public $allow;

    /**
     * Nome do menu
     * @var string
     */
    public $label;

    /**
     * Incone do menu
     * @var string
     */
    public $icon;

    /**
     * Modulo do usuário
     * @var string
     */
    public $module;

    /**
     * Path Modulo do usuário
     * @var string
     */
    public $path_module;

    /**
     * ID Modulo do usuário
     * @var integer
     */
    public $module_id;

    /**
     * Nome do usuário
     * @var string
     */
    public $description;

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
        $dtCriacao = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCriacao->setTimezone(new \DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'module_id' => $this->module_id,
            'level_id' => $this->level_id,
            'allow' => $this->allow,
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
        $dtUpdate = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new \DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'module_id' => $this->module_id,
            'level_id' => $this->level_id,
            'allow' => $this->allow,
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
     * Método responsável por retornar um level pelo ID
     * @param integer $id
     * @return AccessModules
     */
    public static function getAccessModuleById($id)
    {
        return self::getAccessModules('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os modules pelo nivel de acesso
     * @param integer $levelId
     * @return AccessModules
     */
    public static function getAccessModuleByLevelId(int $levelId)
    {
        return self::getAccessModules('level_id = '.$levelId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um level com base no seu nome
     * @param integer $levelId
     * @param int $id
     * @return AccessModules
     */
    public static function getAccessModuleByIdByLevelId(int $levelId, int $id)
    {
        return self::getAccessModules('level_id = '.$levelId . ' AND module_id = '.$id )->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os modules
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getAccessModules($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_access_modules'))->select($where,$order,$limit,$group,$fields);
    }
}