<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class TempUser
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_temp_users';
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
     * Departamento do usuário
     * @var integer
     */
    public int $department_id;

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
     * Nome do departamento
     * @var string
     */
    public string $department;

    /**
     * Diretor do departamento
     * @var string
     */
    public string $department_director;

    /**
     * Telefone Diretor do departamento
     * @var string
     */
    public string $phone_number;

    /**
     * Telefone Diretor do departamento
     * @var string
     */
    public string $phone_number_mask;

    /**
     * Data criação usuário
     * @var DateTime|string|null
     */
    public DateTime|string|null $created_at;

    /**
     * Data de atualização usuário
     * @var DateTime|string|null
     */
    public DateTime|string|null $updated_at;

    /**
     * Id do usuário criado na tabela de usuário
     * @var null|integer
     */
    public null|int $user_id;

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
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'login' => $this->login,
            'department_id' => $this->department_id,
            'id_status' => $this->id_status,
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
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'login' => $this->login,
            'department_id' => $this->department_id,
            'id_status' => $this->id_status,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
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
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'id_status' => $this->id_status,
            'user_id' => $this->user_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateReproved()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'id_status' => $this->id_status,
            'email' => $this->email,
            'login' => $this->login,
            'user_id' => $this->user_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
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
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'password' => $this->password,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
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
        $this->updated_at = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'email' => $this->email,
            'login' => $this->login,
            'id_status' => $this->id_status,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
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
     * Método responsável por retornar um usuário pelo ID
     * @param integer $id
     * @return TempUser
     */
    public static function getUserByUserId(int $id)
    {
        return self::getTempUsers('user_id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário pelo ID
     * @param integer $id
     * @return TempUser
     */
    public static function getUserById(int $id)
    {
        return self::getTempUsers('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retonar um usuário com base no seu e-mail
     * @param string $email
     * @return TempUser
     */
    public static function getUserByEmail(string $email)
    {
        return self::getTempUsers('email = "'.$email.'" AND id_status')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base no seu e-mail somente usado nas APIs
     * @param string $email
     * @return TempUser
     */
    public static function getUserByUsername(string $email)
    {
        return self::getTempUsers('email = "'.$email.'" AND id_status')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base no seu login
     * @param string $login
     * @return TempUser
     */
    public static function getTempUserByLogin(string $login)
    {
        return self::getTempUsers('login = "'.$login.'"  AND id_status')->fetchObject(self::class);
    }

    /**
     * Método responsável por verificar se existe usuário cadastro pelo departamento
     * @param int $departmentId
     * @return TempUser
     */
    public static function getTempUserByIdDepartment(int $departmentId)
    {
        return self::getTempUsers('department_id = '.$departmentId,null,1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string|null $where
     * @param string|null $order
     * @param string|null $group
     * @param string|null $limit
     */
    public static function getTempUsers(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'): PDOStatement
    {
        return (new Database('cnt_temp_users'))->select($where,$order,$limit,$group,$fields);
    }
}