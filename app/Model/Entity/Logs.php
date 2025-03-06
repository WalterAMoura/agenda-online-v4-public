<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class Logs
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_log';
    /**
     * ‘ID’
     * @var integer
     */
    public $id;

    /**
     * Id usuário
     * @var integer
     */
    public $id_user;

    /**
     * @var string
     */
    public $application;

    /**
     * @var string
     */
    public $data;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $token;

    /**
     * Data criação
     * @var DateTime|string
     */
    public $created_at;


    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $createdAt = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $createdAt->setTimezone(new \DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'id_user' => $this->id_user,
            'application' => $this->application,
            'data' => $this->data,
            'token' => $this->token,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ]);

        // Success
        return true;
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getLogs($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_logs'))->select($where,$order,$limit,$group,$fields);
    }
}