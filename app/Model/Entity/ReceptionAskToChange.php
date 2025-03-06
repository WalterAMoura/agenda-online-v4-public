<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class ReceptionAskToChange
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_reception_ask_to_change';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Id do usuário atual vinculado a essa agenda
     * @var integer
     */
    public $current_linked_user_id;

    /**
     * Id da escala
     * @var integer
     */
    public $scheduler_id;

    /**
     * Id do novo usuário vinculado a essa agenda
     * @var integer
     */
    public $new_linked_user_id;

    /**
     * Id do status
     * @var integer
     */
    public $status;

    /**
     * Comentários escrito pelo novo
     * @var string
     */
    public $comments;

    /**
     * Nome do usuário atual
     * @var string
     */
    public $current_linked_user_name;

    /**
     * Nome do novo usuário atual
     * @var string
     */
    public $new_linked_user_name;

    /**
     * Nome do status
     * @var string
     */
    public $status_name;

    /**
     * Nome do dia da semana
     * @var string
     */
    public $scheduler_day_long_description;

    /**
     * Procedure a ser executada
     * @var string
     */
    public $procedure;

    /**
     * Data do agendamento
     * @var DateTime|string
     */
    public $scheduler_date;

    /**
     * Total de registros
     * @var integer
     */
    public $total_records;

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
            'current_linked_user_id' => $this->current_linked_user_id,
            'new_linked_user_id' => $this->new_linked_user_id,
            'scheduler_id' => $this->scheduler_id,
            'status' => $this->status,
            'comments' => $this->comments,
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
            'current_linked_user_id' => $this->current_linked_user_id,
            'new_linked_user_id' => $this->new_linked_user_id,
            'scheduler_id' => $this->scheduler_id,
            'status' => $this->status,
            'comments' => $this->comments,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateStatus()
    {
        $dtUpdate = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new \DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'status' => $this->status,
            'comments' => $this->comments,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluir dados no banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * @return PDOStatement
     */
    public function call()
    {
        return (new Database())->proc($this->procedure);
    }

    /**
     * Método responsável por retornar pelo ID
     * @param integer $id
     * @return ReceptionAskToChange
     */
    public static function getAskToChangeById(int $id)
    {
        return self::getAskToChange('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar pelo ID
     * @param integer $id
     * @return ReceptionAskToChange
     */
    public static function getAskToChangeBySchedulerId(int $id)
    {
        return self::getAskToChange('scheduler_id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getAskToChange($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_ask_to_change_reception'))->select($where,$order,$limit,$group,$fields);
    }
}