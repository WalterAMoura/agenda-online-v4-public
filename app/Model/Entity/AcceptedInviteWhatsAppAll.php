<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use App\Utils\Debug;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class AcceptedInviteWhatsAppAll
{

    /**
     * Id tabela
     * @var integer
     */
    public int $id;

    /**
     * Id da escala
     * @var integer
     */
    public int $scheduler_id;

    /**
     * Id usuário sonoplastia
     * @var integer
     */
    public int $team_id;

    /**
     * Número do telefone que enviado a mensagem
     * @var string
     */
    public string $contato;

    /**
     * Id da mensagem
     * @var string
     */
    public string $message_id;

    /**
     * Status invite
     * @var string
     */
    public string $status;

    /**
     * Nome completo usuário sonoplastia
     * @var string
     */
    public string $complete_name;

    /**
     * Nome reduzido usuário sonoplastia
     * @var string
     */
    public string $name;

    /**
     * Dia da semana
     * @var string
     */
    public string $day_long_description;

    /**
     * Tipo do time notificado
     * @var string
     */
    public string $team_type;

    /**
     * Tracking message
     * @var string|DateTime
     */
    public string|DateTime $scheduler_date;

    /**
     * Tracking message
     * @var string|DateTime
     */
    public string|DateTime $timestamp_accepted;

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
     * @return AcceptedInviteWhatsAppAll
     */
    public static function getSendMessageById(int $id)
    {
        return self::getAcceptedInvite('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar as informações da mensagem enviada para o banco de dados
     * @param int $id
     * @param string $teamType
     * @return AcceptedInviteWhatsAppAll
     */
    public static function getSendMessageByIdByTeamType(int $id, string $teamType)
    {
        return self::getAcceptedInvite('id = '.$id . ' AND team_type COLLATE utf8mb4_unicode_ci = "'.$teamType.'" COLLATE utf8mb4_unicode_ci')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar as informações da mensagem enviada para o banco de dados
     * @param int $schedulerid
     * @return AcceptedInviteWhatsAppAll
     */
    public static function getSendMessageBySchedulerId(int $schedulerid)
    {
        return self::getAcceptedInvite('scheduler_id = '.$schedulerid)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os dados pelo id da mensagem
     * @param string $messageId
     * @return AcceptedInviteWhatsAppAll
     */
    public static function getSendMessageByIdMessage($messageId)
    {
        $order = 'id DESC';
        $limit = 1;
        return self::getAcceptedInvite('message_id = "'.$messageId.'"', $order, $limit)->fetchObject(self::class);
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
    public static function getAcceptedInvite(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'):PDOStatement
    {
        return (new Database('cnt_control_accepted_invite_all'))->select($where,$order,$limit,$group,$fields);
    }
}