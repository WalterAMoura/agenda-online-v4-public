<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class Level
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_access_level';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário
     * @var string
     */
    public $description;

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
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $dtCriacao = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCriacao->setTimezone(new \DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'level' => $this->level,
            'description' => $this->description,
            'home_path' => $this->home_path,
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
            'level' => $this->level,
            'description' => $this->description,
            'home_path' => $this->home_path,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluír um level do banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar um level pelo ID
     * @param integer $id
     * @return Level
     */
    public static function getLevelById($id)
    {
        return self::getLevels('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um level com base no seu nome
     * @param string $name
     * @return Level
     */
    public static function getLevelByName($name)
    {
        return self::getLevels('description = "'.$name.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um level pelo level
     * @param integer $level
     * @return Level
     */
    public static function getLevelByLevel($level)
    {
        return self::getLevels('level = '.$level)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getLevels($where = null, $order = null, $limit = null, $group = null, $fields = '*')
    {
        return (new Database('tb_access_level'))->select($where,$order,$limit,$group,$fields);
    }
}