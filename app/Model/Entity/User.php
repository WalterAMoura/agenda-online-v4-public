<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class User
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_users';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public int $id;

    /**
     * Nome do usuário
     * @var string
     */
    public string $name;

    /**
     * Email do usuário
     * @var string
     */
    public string $email;

    /**
     * Senha do usuário
     * @var string
     */
    public string $password;

    /**
     * Login do usuário
     * @var string
     */
    public string $login;

    /**
     * Nivel do usuário
     * @var integer
     */
    public int $id_nivel;

    /**
     * ID estado usuário
     * @var integer
     */
    public int $id_status;

    /**
     * Estado do usuário na aplicação
     * @var string
     */
    public string $status_user;

    /**
     * Path de login do usuário
     * @var string $home_path
     */
    public string $home_path;

    /**
     * Data criação usuário
     * @var DateTime
     */
    public DateTime $dataCriacao;

    /**
     * Data de atualização usuário
     * @var DateTime
     */
    public DateTime $dataUpdate;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $dtCriacao = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->dataCriacao = $dtCriacao->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'login' => $this->login,
            'id_nivel' => $this->id_nivel,
            'id_status' => $this->id_status,
            'created_at' => $this->dataCriacao->format('Y-m-d H:i:s')
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
        $this->dataUpdate = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'name' => $this->name,
            'email' => $this->email,
            //'password' => $this->password,
            'login' => $this->login,
            'id_nivel' => $this->id_nivel,
            'id_status' => $this->id_status,
            'updated_at' => $this->dataUpdate->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateActiveted()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->dataUpdate = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'id_status' => $this->id_status,
            'updated_at' => $this->dataUpdate->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updatePasswd()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->dataUpdate = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'password' => $this->password,
            'updated_at' => $this->dataUpdate->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável atualizar o status para DELETED
     * @return boolean
     * @throws Exception
     */
    public function updateDeleted()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->dataUpdate = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'email' => $this->email,
            'login' => $this->login,
            'id_status' => $this->id_status,
            'updated_at' => $this->dataUpdate->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluír um usuário do banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar um usuário pelo ID
     * @param integer $id
     * @return User
     */
    public static function getUserById(int $id)
    {
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um usuário com base no seu e-mail
     * @param string $email
     * @return User
     */
    public static function getUserByEmail(string $email)
    {
        return self::getUsers('email = "'.$email.'" AND id_status')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base no seu e-mail somente usado nas APIs
     * @param string $email
     * @return User
     */
    public static function getUserByUsername(string $email)
    {
        return self::getUsers('email = "'.$email.'" AND id_status')->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um usuário com base no seu login
     * @param string $login
     * @return User
     */
    public static function getUserByLogin(string $login)
    {
        return self::getUsers('login = "'.$login.'"  AND id_status')->fetchObject(self::class);
    }

    /**
     * Método responsável por verificar se existe usuário cadastro com level
     * @param int $idLevel
     * @return User
     */
    public static function getUserByIdLevel(int $idLevel)
    {
        return self::getUsers('id_nivel = '.$idLevel,null,1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string|null $where
     * @param string|null $order
     * @param string|null $group
     * @param string|null $limit
     */
    public static function getUsers(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'): PDOStatement
    {
        return (new Database('cnt_users'))->select($where,$order,$limit,$group,$fields);
    }
}