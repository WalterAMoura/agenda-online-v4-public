<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class ElderMonth
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_elder_month';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Id do ancião
     * @var integer
     */
    public $elder_id;

    /**
     * Id do mês
     * @var integer
     */
    public $month_id;

    /**
     * ID do ano
     * @var integer
     */
    public $year_id;


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
            'elder_id' => $this->elder_id,
            'month_id' => $this->month_id,
            'year_id' => $this->year_id,
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
            'elder_id' => $this->elder_id,
            'month_id' => $this->month_id,
            'year_id' => $this->year_id,
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
     * Método responsável por retornar por mes e ano
     * @param int $month
     * @param int $year
     * @return ElderMonth
     */
    public static function getElderMonthByMonth(int $month,int $year)
    {
        return self::getElderMonth('month_id = '. $month . ' AND year_id = '.$year)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar por mes e ano
     * @param int $elderdId
     * @param int $month
     * @param int $year
     * @return PDOStatement
     */
    public static function getElderMonthByElderId(int $elderdId, int $month,int $year)
    {
        return self::getElderMonth('elder_id = '.$elderdId .' AND month_id = '. $month . ' AND year_id = '.$year);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getElderMonth($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_elder_month'))->select($where,$order,$limit,$group,$fields);
    }
}