<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class ElderForDepartment
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_elder_for_department';
    /**
     * ‘ID’
     * @var integer
     */
    public $id;

    /**
     * Id do departamento
     * @var integer
     */
    public $department_id;

    /**
     * Nome do departamento
     * @var string
     */
    public $department_name;

    /**
     * Id do ancião responsável pelo departamento
     * @var integer
     */
    public $elder_id;

    /**
     * Nome completo do ancião responsável
     * @var string
     */
    public $complete_name;

    /**
     * Nome do ancião responsável
     * @var string
     */
    public $name;

    /**
     * Contato do ancião responsável
     * @var string
     */
    public $contato;

    /**
     * Contato do ancião responsável
     * @var string
     */
    public $phone_mask;

    /**
     * Nome do diretor responsável
     * @var string
     */
    public $department_director;

    /**
     * Contato do diretor responsável
     * @var string
     */
    public $director_phone_number;

    /**
     * Contato do diretor responsável
     * @var string
     */
    public $director_phone_number_mask;

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
            'department_id' => $this->department_id,
            'elder_id' => $this->elder_id,
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
            'department_id' => $this->department_id,
            'elder_id' => $this->elder_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluir um status user do banco de dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retornar um status pelo ID
     * @param integer $id
     * @return ElderForDepartment
     */
    public static function getElderForDepartmentById($id)
    {
        return self::getElderForDepartment('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um status pelo ID do departamento
     * @param int $departmentId
     * @return ElderForDepartment
     */
    public static function getElderForDepartmentByDepartmentId(int $departmentId)
    {
        return self::getElderForDepartment('department_id = '.$departmentId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar todos os departamentos por ancião
     * @param integer $elderId
     * @return ElderForDepartment
     */
    public static function getElderForDepartmentByElder(int $elderId)
    {
        return self::getElderForDepartment('elder_id = ' . $elderId)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getElderForDepartment($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_elder_for_department'))->select($where,$order,$limit,$group,$fields);
    }
}