<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class SettingsSmtp
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_settings_smtp';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Apikey associada
     * @var integer
     */
    public $id_apikey;

    /**
     * Porta SMTP
     * @var integer
     */
    public $port;

    /**
     * Host SMTP
     * @var string
     */
    public $host;

    /**
     * Usuário SMTP
     * @var string
     */
    public $username;

    /**
     * Senha do usuário SMTP
     * @var string
     */
    public $password;

    /**
     * Nome do usuário ou Serviço SMTP
     * @var string
     */
    public $from_name;

    /**
     * Descrição status
     * @var string
     */
    public $status_description;

    /**
     * Status Id
     * @var integer
     */
    public $status_id;

    /**
     * ApiKEY Serviço SMTP
     * @var string
     */
    public $api_key;

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
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'from_name' => $this->from_name,
            'id_apikey' => $this->id_apikey,
            'status_id' => $this->status_id,
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
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'password' => $this->password,
            'from_name' => $this->from_name,
            'id_apikey' => $this->id_apikey,
            'status_id' => $this->status_id,
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
     * @return SettingsSmtp
     */
    public static function getSettingsSmtpById(int $settingsSmtpId)
    {
        return self::getSettingsSmtp('id = '. $settingsSmtpId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um mês pelo número
     * @return SettingsSmtp
     */
    public static function getSettingsSmtpActive()
    {
        return self::getSettingsSmtp('status_id = 1',null,1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getSettingsSmtp($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_settings_smtp'))->select($where,$order,$limit,$group,$fields);
    }
}