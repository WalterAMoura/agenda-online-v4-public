<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class EventsChurch
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_events_church';

    /**
     * ‘ID’ do nosso evento
     * @var integer
     */
    public int $id;

    /**
     * Titulo a ser salvo na agenda - concat orador + description
     * @var string
     */
    public string $title;

    /**
     * Tema do sermão
     * @var string
     */
    public string $description;

    /**
     * Cor do evento
     * @var string
     */
    public string $color;

    /**
     * Telefone formatado
     * @var string
     */
    public string $phone_mask;

    /**
     * Telefone sem formato
     * @var string
     */
    public string $contato;

    /**
     * Status agendamento
     * @var string
     */
    public string $status;

    /**
     * Status agendamento
     * @var int
     */
    public int $status_id;

    /**
     * Orador ou nome do evento
     * @var string
     */
    public string $owner;

    /**
     * Cor do texto na agenda
     * @var string
     */
    public string   $textColor;

    /**
     * @var string
     */
    public string $description_status;

    /**
     * Observações relacionado ao evento
     * @var string|null
     */
    public string|null $observacoes;

    /**
     * Departamento responsável pelo evento
     * @var string|null
     */
    public string|null $department;

    /**
     * Id Departamento responsável pelo evento
     * @var int
     */
    public int $department_id;

    /**
     * Programção especial
     * @var string|null
     */
    public string|null $program;

    /**
     * Id Programção especial
     * @var int
     */
    public int $program_id;

    /**
     * Número do mês
     * @var integer
     */
    public int $month;

    /**
     * Descrição curta do mês
     * @var string
     */
    public string $month_short_description;

    /**
     * Descrição larga do mês
     * @var string
     */
    public string $month_long_description;

    /**
     * Número do dia
     * @var integer
     */
    public int $day_of_week;

    /**
     * Descrição curta do dia
     * @var string
     */
    public string $day_of_week_short_description;

    /**
     * Descrição larga do dia
     * @var string
     */
    public string $day_of_week_long_description;

    /**
     * Id do ancião conselheiro
     * @var int|null
     */
    public int|null $elder_id;

    /**
     * Nome completo do ancião conselheiro
     * @var string|null
     */
    public string|null $elder_complete_name;

    /**
     * Nome do ancião conselheiro
     * @var string|null
     */
    public string|null $elder_name;

    /**
     * Contato do ancião conselheiro
     * @var string|null
     */
    public string|null $elder_phone_mask;

    /**
     * Data fim evento
     * @var DateTime|string
     */
    public DateTime|string $end;

    /**
     * Data inicio evento
     * @var DateTime|string
     */
    public DateTime|string $start;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $this->id = (new Database($this->tabela))->insert([
            'title' => $this->title,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'contato' => $this->contato,
            'status_id' => $this->status_id,
            'owner' => $this->owner,
            'department_id' => $this->department_id,
            'program_id' => $this->program_id,
            'observacoes' => $this->observacoes
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
            'title' => $this->title,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'contato' => $this->contato,
            'status_id' => $this->status_id,
            'owner' => $this->owner,
            'department_id' => $this->department_id,
            'program_id' => $this->program_id,
            'observacoes' => $this->observacoes
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateResizeDrop()
    {
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'start' => $this->start,
            'end' => $this->end
        ]);
    }

    /**
     * Método responsável excluír um evento do banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar um evento pelo ID
     * @param integer $id
     * @return User
     */
    public static function getEventById(int $id)
    {
        return self::getEvents('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um status evento pelo ID
     * @param integer $id
     * @return User
     */
    public static function getEventStatusById(int $id)
    {
        return self::getEvents('status_id = '.$id,null,1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um departamento pelo ID
     * @param integer $id
     * @return User
     */
    public static function getEventDepartmentById(int $id)
    {
        return self::getEvents('department_id = '.$id,null,1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um programa pelo ID
     * @param integer $id
     * @return User
     */
    public static function getEventProgramById(int $id)
    {
        return self::getEvents('program_id = '.$id,null,1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar eventos
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string|null $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getEvents(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*')
    {
        return (new Database('cnt_events_church'))->select($where,$order,$limit,$group,$fields);
    }
}