<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use App\Utils\Debug;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class Singers
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_singers';
    /**
     * ‘ID’
     * @var integer
     */
    public $id;

    /**
     * Nome do cantor convidado
     * @var string
     */
    public $singer;

    /**
     * Data criação
     * @var DateTime|string
     */
    public $created_at;

    /**
     * Data última atualização
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
        $dtCreate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCreate->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'singer' => $this->singer,
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
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'singer' => $this->singer,
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
     * @return Singers
     */
    public static function getSingerById($id)
    {
        return self::getSigers('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um id pelo nome do cantor
     * @param string $name
     * @return Singers
     */
    public static function getSingerByName($name)
    {
        return self::getSigers('singer = "'.$name."'")->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getSigers($where = null, $order = null, $limit = null, $group = null, $fields = '*')
    {
        return (new Database('tb_singers'))->select($where,$order,$limit,$group,$fields);
    }
}