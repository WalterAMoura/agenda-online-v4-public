<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class EventTextColor
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_text_color';
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
     * Método responsável por atualizar cor os dados no banco
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
     * Método responsável excluír um cor do banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar uma cor pelo ID
     * @param integer $id
     * @return EventBackColor
     */
    public static function getColorTextEventById($id)
    {
        return self::getColorTextEvents('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar uma cor com base no seu nome
     * @param string $colorName
     * @return EventBackColor
     */
    public static function getColorTextEventByName($colorName)
    {
        return self::getColorTextEvents('color = "'.$colorName.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar a tabela
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getColorTextEvents($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_text_color'))->select($where,$order,$limit,$group,$fields);
    }
}