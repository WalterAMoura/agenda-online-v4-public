<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class TypeModules
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_type_module';
    /**
     * ‘ID’ do nosso modulo
     * @var integer
     */
    public $id;

    /**
     * tipo modulo
     * @var string
     */
    public $type;

    /**
     * Descrição modulo
     * @var string
     */
    public $description;

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
            'type' => $this->type,
            'description' => $this->description,
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
            'type' => $this->type,
            'description' => $this->description,
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
     * Método responsável por retornar um tipo modulo pelo ID
     * @param integer $id
     * @return TypeModules
     */
    public static function getTypeModuleById($id)
    {
        return self::getTypeModules('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um tipo modulo pelo tipo
     * @param string $type
     * @return TypeModules
     */
    public static function getTypeModuleByType(string $type)
    {
        return self::getTypeModules('type = "'.$type . '"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um tipo modulo pelo descrição
     * @param string $description
     * @return TypeModules
     */
    public static function getTypeModuleByDescription(string $description)
    {
        return self::getTypeModules('description = "'.$description . '"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os modules
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getTypeModules($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_type_module'))->select($where,$order,$limit,$group,$fields);
    }
}