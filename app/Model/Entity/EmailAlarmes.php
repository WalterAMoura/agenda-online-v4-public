<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class EmailAlarmes
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_email_alarmes';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Email verificado
     * @var integer
     */
    public $email_verified;

    /**
     * E-mail
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * Status Id
     * @var integer
     */
    public $status_id;

    /**
     * Status
     * @var integer
     */
    public $status;

    /**
     * @var integer
     */
    public $status_verified_id;

    /**
     * Descrição ativação email
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
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'email_verified' => $this->email_verified,
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
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'email_verified' => $this->email_verified,
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
     * @return EmailAlarmes
     */
    public static function getEmailAlarmesById($settingsSmtpId)
    {
        return self::getEmailAlarmes('id = '.$settingsSmtpId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getEmailAlarmes($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_email_alarmes'))->select($where,$order,$limit,$group,$fields);
    }
}