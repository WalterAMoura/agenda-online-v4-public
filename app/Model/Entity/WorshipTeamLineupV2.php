<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use PDOStatement;

class WorshipTeamLineupV2
{
    /**
     * Tabela usada pela classe (agora é a VIEW)
     * @var string
     */
    private $tabela = 'cnt_worship_team_schedule_v2'; // Usando a view

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Data de criação
     * @var DateTime|string
     */
    public $created_at;

    /**
     * Data de atualização
     * @var DateTime|string
     */
    public $updated_at;

    /**
     * Data agendada
     * @var DateTime|string
     */
    public $scheduler_date;

    /**
     * Id dia da semana
     * @var integer
     */
    public $day_id;

    /**
     * Dia da semana (1-7, domingo-sábado)
     * @var integer
     */
    public $day_of_week;

    /**
     * Descrição curta do dia da semana
     * @var string
     */
    public $day_short_description;

    /**
     * Descrição longa do dia da semana
     * @var string
     */
    public $day_long_description;

    /**
     * Dia do mês
     * @var integer
     */
    public $day;

    /**
     * Mês (número de 1 a 12)
     * @var integer
     */
    public $month;

    /**
     * Descrição curta do mês
     * @var string
     */
    public $month_short_description;

    /**
     * Descrição longa do mês
     * @var string
     */
    public $month_long_description;

    /**
     * Ano
     * @var integer
     */
    public $year;

    /**
     * IDs do time de adoração
     * @var string
     */
    public $group_worship_team_ids;

    /**
     * Nomes completos dos membros do time de adoração
     * @var string
     */
    public $group_complete_names;

    /**
     * Nomes do time de adoração
     * @var string
     */
    public $group_names;

    /**
     * IDs dos cantores
     * @var string
     */
    public $group_singer_ids;

    /**
     * Nomes dos cantores
     * @var string
     */
    public $group_singer_names;

    /**
     * Música de adoração
     * @var string
     */
    public $worship_music;

    /**
     * Música dos cantores
     * @var string
     */
    public $singer_music;

    /**
     * Método responsável por retornar pelo ID
     * @param int $schedulerId
     * @return WorshipTeamLineupV2
     */
    public static function getWorshipTeamLineupById(int $schedulerId)
    {
        return self::getWorshipTeamLineup('id = '.$schedulerId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os dados da VIEW
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getWorshipTeamLineup($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_worship_team_schedule_v2'))->select($where, $order, $limit, $group, $fields);
    }
}
