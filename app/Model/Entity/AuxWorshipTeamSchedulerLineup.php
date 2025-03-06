<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class AuxWorshipTeamSchedulerLineup
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_aux_worship_team_scheduler_lineup';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * ‘ID’ da referenciando a escala do louvor
     * @var integer
     */
    public $worship_team_scheduler_id ;

    /**
     * Id equipe louvor
     * @var integer
     */
    public $worship_team_id;

    /**
     * @var string
     */
    public $group_complete_names;

    /**
     * @var string
     */
    public $group_names;

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
            'worship_team_scheduler_id' => $this->worship_team_scheduler_id,
            'worship_team_id' => $this->worship_team_id,
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
            'worship_team_scheduler_id' => $this->worship_team_scheduler_id,
            'worship_team_id' => $this->worship_team_id,
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
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getAuxWorshipTeamLineup($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_aux_worship_team_scheduler_lineup'))->select($where,$order,$limit,$group,$fields);
    }
}