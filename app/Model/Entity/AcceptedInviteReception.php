<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class AcceptedInviteReception
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_control_accepted_invite_reception';

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
    public int $receptionteam_id;

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
            'scheduler_id' => $this->scheduler_id,
            'receptionteam_id' => $this->receptionteam_id,
            'message_id' => $this->message_id,
            'status' => $this->status,
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
            'scheduler_id' => $this->scheduler_id,
            'receptionteam_id' => $this->receptionteam_id,
            'message_id' => $this->message_id,
            'status' => $this->status,
            'timestamp_accepted' => $this->timestamp_accepted,
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
            'status' => $this->status,
            'timestamp_accepted' => $this->timestamp_accepted,
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
     * @return AcceptedInviteReception
     */
    public static function getSendMessageById(int $id)
    {
        return self::getAcceptedInvite('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar as informações da mensagem enviada para o banco de dados
     * @param int $schedulerid
     * @return AcceptedInviteReception
     */
    public static function getSendMessageBySchedulerId(int $schedulerid)
    {
        return self::getAcceptedInvite('scheduler_id = '.$schedulerid)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os dados pelo id da mensagem
     * @param string $messageId
     * @return AcceptedInviteReception
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
        return (new Database('cnt_control_accepted_invite_reception'))->select($where,$order,$limit,$group,$fields);
    }
}