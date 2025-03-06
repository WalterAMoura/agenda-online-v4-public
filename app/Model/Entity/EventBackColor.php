<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class EventBackColor
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_color';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * Background color
     * @var string
     */
    public $color;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $this->id = (new Database($this->tabela))->insert([
            'color' => $this->color
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
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'color' => $this->color
        ]);
    }

    /**
     * Método responsável excluír um back color do banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar um back color pelo ID
     * @param integer $id
     * @return EventBackColor
     */
    public static function getColorBackEventById($id)
    {
        return self::getColorBackEvents('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um back color com base no seu nome
     * @param string $colorName
     * @return EventBackColor
     */
    public static function getColorBackEventByName($colorName)
    {
        return self::getColorBackEvents('color = "'.$colorName.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar back color
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getColorBackEvents($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_color'))->select($where,$order,$limit,$group,$fields);
    }
}