<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use App\Utils\Debug;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class Visitor
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_visitor';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public $id;

    /**
     * Id do usuário
     * @var integer
     */
    public $id_user;

    /**
     * Nivel do usuário
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $name_user;

    /**
     * Data criação usuário
     * @var DateTime|string
     */
    public $start_date;

    /**
     * Data fim da visita
     * @var DateTime|string
     */
    public $end_date;


    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $dtCriacao = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->start_date = $dtCriacao->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'start_date' => $this->start_date->format('Y-m-d H:i:s'),
            'end_date' => $this->start_date->format('Y-m-d H:i:s'),
            'id_user' => $this->id_user,
            'login' => $this->login,
            'name_user' => $this->name_user
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
        $this->end_date = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'end_date' => $this->end_date->format('Y-m-d H:i:s'),
            'id_user' => $this->id_user,
            'login' => $this->login,
            'name_user' => $this->name_user
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
     * Método responsável por retonar um level pelo ID
     * @param integer $id
     * @return Visitor
     */
    public static function getVisitorById($id)
    {
        return self::getVisitors('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um level com base no seu nome
     * @param string $login
     * @return Visitor
     */
    public static function getVisitorByLogin($login)
    {
        return self::getVisitors('login = "'.$login.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um level pelo level
     * @param integer $idUser
     * @return Visitor
     */
    public static function getVistorByIdUser($idUser)
    {
        return self::getVisitors('id_user = '.$idUser.' AND end_date >= "2023-05-04 14:53:00"')->fetchObject(self::class);
    }

    /**
     * @param integer $idUser
     * @param string $date
     * @return Visitor
     */
    public static function getUserOnline($idUser,$date)
    {
        return self::getVisitors('id_user = '.$idUser.' AND end_date >= "'.$date.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getVisitors($where = null, $order = null, $limit = null, $group = null, $fields = '*')
    {
        return (new Database('tb_visitor'))->select($where,$order,$limit,$group,$fields);
    }
}