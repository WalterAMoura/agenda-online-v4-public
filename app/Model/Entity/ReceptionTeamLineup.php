<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class ReceptionTeamLineup
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_reception_team_schedule';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * id Usuário vinculado
     * @var integer
     */
    public $linked_user_id;

    /**
     * Nome do usuário vinculado
     * @var string
     */
    public $linked_user_name;


    /**
     * Id pessoa
     * @var integer
     */
    public $reception_team_id;


    /**
     * id dia da semana
     * @var integer
     */
    public $day_of_week;

    /**
     * Descrição curta dia da semana
     * @var string
     */
    public $day_short_description;

    /**
     * Descrição longa dia da semana
     * @var string
     */
    public $day_long_description;

    /**
     * dia
     * @var integer
     */
    public $day;

    /**
     * mês
     * @var integer
     */
    public $month;

    /**
     * Descrição curta mês
     * @var string
     */
    public $month_short_description;

    /**
     * Descrição longa mês
     * @var string
     */
    public $month_long_description;

    /**
     * mês
     * @var integer
     */
    public $year;

    /**
     * Nome completo
     * @var string
     */
    public $completed_name;

    /**
     * Nome
     * @var string
     */
    public $name;

    /**
     * Telefone
     * @var string
     */
    public $contato;

    /**
     * Telefone com máscara
     * @var string
     */
    public $phone_mask;

    /**
     * E-mail
     * @var string
     */
    public $email;

    /**
     * Data agendada
     * @var DateTime|string
     */
    public $scheduler_date;

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
        $dtCriacao = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $dtCriacao->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'scheduler_date' => $this->scheduler_date,
            'reception_team_id' => $this->reception_team_id,
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
            'scheduler_date' => $this->scheduler_date,
            'reception_team_id' => $this->reception_team_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateNewLinkedUser()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'reception_team_id' => $this->reception_team_id,
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
     * @return ReceptionTeamLineup
     */
    public static function getReceptionTeamLineupById(int $id)
    {
        return self::getReceptionTeamLineup('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getReceptionTeamLineup($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_reception_team_schedule'))->select($where,$order,$limit,$group,$fields);
    }
}