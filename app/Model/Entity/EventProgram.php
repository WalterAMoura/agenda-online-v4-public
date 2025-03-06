<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class EventProgram
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_programs';
    /**
     * Id do programa
     * @var integer
     */
    public $id;

    /**
     * Nome do programa ou evento especial
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
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'description' => $this->description
        ]);

        // Success
        return true;
    }

    /**
     * Método responsável por atualizar cor os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function atualizar()
    {
        $dtUpdate = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new \DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'description' => $this->description
        ]);
    }

    /**
     * Método responsável excluír um programa do banco de dados
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar um programa pelo id
     * @param integer $id
     * @return EventProgram
     */
    public static function getProgramEventById($id)
    {
        return self::getProgramEvents('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar uma cor com base no seu nome
     * @param string $description
     * @return EventProgram
     */
    public static function getProgramEventByName($description)
    {
        return self::getProgramEvents('description = "'.$description.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar a tabela
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getProgramEvents($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_programs'))->select($where,$order,$limit,$group,$fields);
    }
}