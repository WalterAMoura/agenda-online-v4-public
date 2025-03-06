<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class PasswordTemp
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_password_temp';

    /**
     * Id
     * @var integer
     */
    public int $id;

    /**
     * Usuário associado
     * @var integer
     */
    public int $id_user;

    /**
     * Senha temporária
     * @var string
     */
    public string $password;

    /**
     * Data de criação
     * @var string|DateTime
     */
    public string|DateTime $created_at;

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
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'id_user' => $this->id_user,
            'password' => $this->password
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
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'id_user' => $this->id_user,
            'password' => $this->password
        ]);
    }

    /**
     * Método responsável por retornar a apikey
     * @param int $userId
     * @return PasswordTemp
     */
    public static function getPasswordTempByUserId(int $userId)
    {
        return self::getPasswordTemp('id_user = '. $userId,' id DESC', 1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string|null $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getPasswordTemp(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'):PDOStatement
    {
        return (new Database('tb_password_temp'))->select($where,$order,$limit,$group,$fields);
    }
}