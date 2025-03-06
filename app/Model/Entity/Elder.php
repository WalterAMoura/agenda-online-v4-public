<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class Elder
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_elder';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Nome do ancião
     * @var string
     */
    public $name;

    /**
     * Nome completo ancião
     * @var string
     */
    public $complete_name;

    /**
     * Contato do ancião
     * @var string
     */
    public $contato;

    /**
     * Contato do ancião com mascára
     * @var string
     */
    public $phone_mask;

    /**
     * id Usuário vinculado
     * @var integer
     */
    public $user_id;

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
            'complete_name' => $this->complete_name,
            'name' => $this->name,
            'contato' => $this->contato,
            'user_id' => $this->user_id,
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
            'complete_name' => $this->complete_name,
            'name' => $this->name,
            'contato' => $this->contato,
            'user_id' => $this->user_id,
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
     * @return Elder
     */
    public static function getElderById($id)
    {
        return self::getElders('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um user pelo ID
     * @param integer $id
     * @return Elder
     */
    public static function getElderByUserId($id)
    {
        return self::getElders('linked_user_id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um ancião pelo nome
     * @param string $name
     * @return Elder
     */
    public static function getElderByName($name)
    {
        return self::getElders('name = "'.$name.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getElders($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_elders'))->select($where,$order,$limit,$group,$fields);
    }
}