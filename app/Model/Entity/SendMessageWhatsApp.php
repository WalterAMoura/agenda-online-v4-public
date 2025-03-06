<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class SendMessageWhatsApp
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_send_message_whatsapp';

    /**
     * Id tabela
     * @var integer
     */
    public int $id;

    /**
     * Id usuário sonoplastia
     * @var integer
     */
    public int $soundteam_id;

    /**
     * Número do telefone que enviado a mensagem
     * @var string
     */
    public string $phone_number_sent;

    /**
     * Id da mensagem
     * @var string
     */
    public string $message_id;

    /**
     * Status da mensagem
     * @var string
     */
    public string $message_status;

    /**
     * PATH acesso da chave APIKEY
     * @var string
     */
    public string $api_path;


    /**
     * Ativa/Inativa APIKEY
     * @var integer
     */
    public int $status_id;

    /**
     * Nome completo usuário sonoplastia
     * @var string
     */
    public string $complete_name;

    /**
     * Nome reduzido usuário sonoplastia
     * @var string
     */
    public string $short_name;

    /**
     * Telefone usuário sonoplastia
     * @var string
     */
    public string $phone_number;

    /**
     * id Usuário vinculado
     * @var integer
     */
    public int $linked_user_id;

    /**
     * login Usuário vinculado
     * @var string
     */
    public string $linked_user_login;

    /**
     * id Usuário vinculado
     * @var integer
     */
    public int $linked_user_level;

    /**
     * email Usuário vinculado
     * @var string
     */
    public string $linked_user_email;

    /**
     * id Usuário vinculado
     * @var integer
     */
    public int $linked_user_status_id;

    /**
     * Payload recebido
     */
    public $payload;

    /**
     * Tracking message
     * @var string|DateTime|null
     */
    public string|DateTime|null $timestamp_message;

    /**
     * Data de criação
     * @var string|DateTime
     */
    public string|DateTime $created_at;

    /**
     * Data de atualização
     * @var string|DateTime
     */
    public string|DateTime $updated_at;

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
            'soundteam_id' => $this->soundteam_id,
            'phone_number_sent' => $this->phone_number_sent,
            'message_id' => $this->message_id,
            'message_status' => $this->message_status,
            'timestamp_message' => $this->timestamp_message,
            'payload' => $this->payload,
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
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'soundteam_id' => $this->soundteam_id,
            'phone_number_sent' => $this->phone_number_sent,
            'message_id' => $this->message_id,
            'message_status' => $this->message_status,
            'timestamp_message' => $this->timestamp_message,
            'payload' => $this->payload,
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateStatus()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'message_status' => $this->message_status,
            'timestamp_message' => $this->timestamp_message,
        ]);
    }

    /**
     * Método responsável remover do banco de dados
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retornar as informações da mensagem enviada para o banco de dados
     * @param int $id
     * @return SendMessageWhatsApp
     */
    public static function getSendMessageById(int $id)
    {
        return self::getSendMessageWhatsApp('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os dados pelo id da mensagem
     * @param string $messageId
     * @return SendMessageWhatsApp
     */
    public static function getSendMessageByIdMessage($messageId)
    {
        $order = 'id DESC';
        $limit = 1;
        return self::getSendMessageWhatsApp('message_id = "'.$messageId.'"', $order, $limit)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma instância do banco de dados
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string|null $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getSendMessageWhatsApp(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'):PDOStatement
    {
        return (new Database('cnt_send_message_whatsapp'))->select($where,$order,$limit,$group,$fields);
    }
}