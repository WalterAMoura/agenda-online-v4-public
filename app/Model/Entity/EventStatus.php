<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class EventStatus
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_events_status';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário
     * @var string
     */
    public $status;

    /**
     * Background color
     * @var integer
     */
    public $color_id;

    /**
     * Cor do texto evento
     * @var integer
     */
    public $text_color_id;

    /**
     * Descrição
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
            'status' => $this->status,
            'description' => $this->description,
            'color_id' => $this->color_id,
            'text_color_id' => $this->text_color_id,
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
            'status' => $this->status,
            'description' => $this->description,
            'color_id' => $this->color_id,
            'text_color_id' => $this->text_color_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluír um level do banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar um level pelo ID
     * @param integer $id
     * @return EventStatus
     */
    public static function getStatusEventById($id)
    {
        return self::getStatusEvents('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um level com base no seu nome
     * @param string $statusEvent
     * @return EventStatus
     */
    public static function getStatusEventByName($statusEvent)
    {
        return self::getStatusEvents('status = "'.$statusEvent.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getStatusEvents($where = null, $order = null, $limit = null, $group = null, $fields = '*')
    {
        return (new Database('cnt_events_status'))->select($where,$order,$limit,$group,$fields);
    }
}