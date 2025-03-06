<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class SuggestedTime
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_suggested_time';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Id do dia semana
     * @var integer
     */
    public $day_of_week_id;

    /**
     * Horário sugerido
     * @var string
     */
    public $suggested_time;

    /**
     * Número do dia semana
     * @var integer
     */
    public $number_day;

    /**
     * Descrição curta
     * @var string
     */
    public $short_description;

    /**
     * Descrição longa
     * @var string
     */
    public $long_description;

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
            'day_of_week_id' => $this->day_of_week_id,
            'suggested_time' => $this->suggested_time,
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
            'day_of_week_id' => $this->day_of_week_id,
            'suggested_time' => $this->suggested_time,
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
     * Método responsável por retornar pelo ID
     * @param integer $id
     * @return SuggestedTime
     */
    public static function getSuggestedTimeById(int $id)
    {
        return self::getSuggestedTime('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma sugerida pelo id do dia da semana
     * @param int $id
     * @return SuggestedTime
     */
    public static function getSuggestedByDayOfWeek(int $id)
    {
        return self::getSuggestedTime('day_of_week_id = '. $id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getSuggestedTime($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_suggested_time'))->select($where,$order,$limit,$group,$fields);
    }
}