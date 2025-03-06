<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class SingersLineup
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_singer_scheduler';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;


    /**
     * Id escala vinculada
     * @var integer
     */
    public $worship_team_scheduler_id;


    /**
     * Id cantor
     * @var integer
     */
    public $singer_id;

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
        $dtCreate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCreate->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'worship_team_schedule_id' => $this->worship_team_scheduler_id,
            'singer_id' => $this->singer_id,
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
            'worship_team_schedule_id' => $this->worship_team_scheduler_id,
            'singer_id' => $this->singer_id,
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
     * @return SingersLineup
     */
    public static function getSingerLineupByScedulerId(int $id)
    {
        return self::getSingerLineup('worship_team_schedule_id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar pelo ID
     * @param integer $id
     * @return SingersLineup
     */
    public static function getSingerLineupById(int $id)
    {
        return self::getSingerLineup('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getSingerLineup($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_singer_scheduler'))->select($where,$order,$limit,$group,$fields);
    }
}