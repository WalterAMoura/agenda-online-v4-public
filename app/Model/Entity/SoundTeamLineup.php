<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class SoundTeamLineup
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_sound_team_schedule';

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
     * Id Horário sugerido
     * @var integer
     */
    public $suggested_time_id;

    /**
     * Id pessoa
     * @var integer
     */
    public $sound_team_id;

    /**
     * id equipamento
     * @var integer
     */
    public $sound_device_id;

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
     * Device
     * @var string
     */
    public $device;

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
            'sound_team_id' => $this->sound_team_id,
            'sound_device_id' => $this->sound_device_id,
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
            'sound_team_id' => $this->sound_team_id,
            'sound_device_id' => $this->sound_device_id,
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
            'sound_team_id' => $this->sound_team_id,
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
     * @return SoundTeamLineup
     */
    public static function getSoundTeamLineupById(int $id)
    {
        return self::getSoundTeamLineup('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma sugerida pelo id do dia da semana
     * @param int $id
     * @return SoundTeamLineup
     */
    public static function getSoundTeamLineupByPersonId(int $id)
    {
        return self::getSoundTeamLineup('sound_device_id = '. $id)->fetchObject(self::class);
    }

    /**
     * Método por validar se existe uma escala para aquele dispositivo
     * @param int $id
     * @param int $day
     * @param int $month
     * @param int $year
     * @return SoundTeamLineup
     */
    public static function getSoundTeamLineupByDeviceId(int $id, int $day, int $month, int $year )
    {
        return self::getSoundTeamLineup('sound_device_id = '. $id . ' AND  day = ' . $day . ' AND month = ' . $month . ' AND  year = ' . $year)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getSoundTeamLineup($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_sound_team_schedule'))->select($where,$order,$limit,$group,$fields);
    }
}