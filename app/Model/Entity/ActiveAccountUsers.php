<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class ActiveAccountUsers
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_active_account_users';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Token ativação
     * @var string
     */
    public $token;

    /**
     * Email verificado
     * @var integer
     */
    public $id_user;

    /**
     * Nome do usuário
     * @var string
     */
    public $name_user;


    /**
     * E-mail
     * @var string
     */
    public $email;

    /**
     * Status token
     * @var integer
     */
    public $status_token;

    /**
     * Descrição status do token
     * @var string
     */
    public $description_status_token;

    /**
     * Data criação token
     * @var DateTime|string
     */
    public $created_at;

    /**
     * Expiração do token
     * @var DateTime|string
     */
    public $expiration_at;

    /**
     * Data de atualização token
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
            'id_user' => $this->id_user,
            'token' => $this->token,
            'expiration_at' => $this->expiration_at,
            'status_token' => $this->status_token,
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
            'id_user' => $this->id_user,
            'token' => $this->token,
            'expiration_at' => $this->expiration_at,
            'status_token' => $this->status_token,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateVerified()
    {
        $dtUpdate = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new \DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'status_token' => $this->status_token,
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
     * Método responsável por retornar um mês pelo número
     * @param integer $settingsSmtpId
     * @return ActiveAccountUsers
     */
    public static function getActiveAccountUsersById($settingsSmtpId)
    {
        return self::getActiveAccountUsers('id = '.$settingsSmtpId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um mês pelo número
     * @param string $token
     * @return ActiveAccountUsers
     */
    public static function getActiveAccountUsersByToken(string $token)
    {
        return self::getActiveAccountUsers('token = "'.$token.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getActiveAccountUsers($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_active_account_users'))->select($where,$order,$limit,$group,$fields);
    }
}