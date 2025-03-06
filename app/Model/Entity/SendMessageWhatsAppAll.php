<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class SendMessageWhatsAppAll
{
    /**
     * Id tabela
     * @var integer
     */
    public int $id;

    /**
     * Id usuário sonoplastia
     * @var integer
     */
    public int $team_id;

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
     * Tipo do time notificado
     * @var string
     */
    public string $team_type;

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
     * Método responsável por retornar as informações da mensagem enviada para o banco de dados
     * @param int $id
     * @return SendMessageWhatsAppAll
     */
    public static function getSendMessageById(int $id)
    {
        return self::getSendMessageWhatsApp('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os dados pelo id da mensagem
     * @param string $messageId
     * @return SendMessageWhatsAppAll
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
        return (new Database('cnt_send_message_whatsapp_all'))->select($where,$order,$limit,$group,$fields);
    }
}