<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class StatusApikey
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_status_apikey';
    /**
     * ‘ID’
     * @var integer
     */
    public $id;

    /**
     * Status do email
     * @var integer
     */
    public $status;

    /**
     * @var string
     */
    public $description;

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
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $dtCriacao = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCriacao->setTimezone(new \DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'status' => $this->status,
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
        $dtUpdate = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new \DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'status' => $this->status,
            'description' => $this->description,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluir um status user do banco de dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retornar um status pelo ID
     * @param integer $id
     * @return StatusApikey
     */
    public static function getStatusApikeyById($id)
    {
        return self::getStatusApikey('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar status pelo nome
     * @param string $statusEmail
     * @return StatusApikey
     */
    public static function getStatusApikeyByName($statusEmail)
    {
        return self::getStatusApikey('description = "'.$statusEmail.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getStatusApikey($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_status_apikey'))->select($where,$order,$limit,$group,$fields);
    }
}