<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class Departaments
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_departments';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * Deparamento ou ministério
     * @var string
     */
    public $department;

    /**
     * Diretor do departamento
     * @var string
     */
    public $department_director;

    /**
     * Telefone com máscara
     * @var string
     */
    public $phone_number;

    /**
     * Telefone com máscara
     * @var string
     */
    public $phone_number_mask;

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
            'department' => $this->department,
            'department_director' => $this->department_director,
            'phone_number' => $this->phone_number,
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
            'department' => $this->department,
            'department_director' => $this->department_director,
            'phone_number' => $this->phone_number,
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
     * Método responsável por retonar um departamento pelo ID
     * @param integer $id
     * @return Departaments
     */
    public static function getDepartmentById($id)
    {
        return self::getDepartments('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um departamento pelo nome
     * @param string $department
     * @return Departaments
     */
    public static function getDepartmentByName($department)
    {
        return self::getDepartments('department = "'.$department.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getDepartments($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_departments'))->select($where,$order,$limit,$group,$fields);
    }
}