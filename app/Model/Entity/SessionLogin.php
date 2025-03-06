<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;
use stdClass;

class SessionLogin
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_sessions_login';
    /**
     * ‘ID’ do nosso usuário
     * @var integer
     */
    public int $id;

    /**
     * @var DateTime|null|string
     */
    public DateTime|null|string $data_inicio;

    /**
     * @var string
     */
    public string $token;

    /**
     * @var int
     */
    public int $id_user;

    /**
     * @var string
     */
    public string $login_user;

    /**
     * @var string
     */
    public string $name_user;

    /**
     * @var string|null
     */
    public string|null $user_agent;

    /**
     * @var string|null
     */
    public string|null $remote_addr;

    /**
     * @var string|null
     */
    public string|null $remote_host;

    /**
     * @var string|null
     */
    public string|null $remote_port;

    /**
     * @var int
     */
    public int $tempo_inativo;

    /**
     * @var DateTime|null|string
     */
    public DateTime|null|string $data_fim;

    /**
     * @var int
     */
    public int $tempo_final;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     * @throws Exception
     */
    public function cadastrar()
    {
        $dtCriacao = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->data_inicio = $dtCriacao->setTimezone(new DateTimeZone('UTC'));
        $this->id = (new Database($this->tabela))->insert([
            'data_inicio' => $this->data_inicio->format('Y-m-d H:i:s'),
            'token' => $this->token,
            'id_user' => $this->id_user,
            'login_user' => $this->login_user,
            'name_user' => $this->name_user,
            'user_agent' => $this->user_agent,
            'remote_addr' => $this->remote_addr,
            'remote_host' => $this->remote_host,
            'remote_port' => $this->remote_port,
            'tempo_inativo' => $this->tempo_inativo,
            'tempo_final' => $this->tempo_final
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
        $this->data_fim = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'data_fim' => $this->data_fim->format('Y-m-d H:i:s'),
            'token' => $this->token,
            'id_user' => $this->id_user,
            'login_user' => $this->login_user,
            'name_user' => $this->name_user,
            'user_agent' => $this->user_agent,
            'remote_addr' => $this->remote_addr,
            'remote_host' => $this->remote_host,
            'remote_port' => $this->remote_port,
            'tempo_inativo' => $this->tempo_inativo,
            'tempo_final' => $this->tempo_final
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateTime()
    {
        $dtUpdate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->data_fim = $dtUpdate->setTimezone(new DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'data_fim' => $this->data_fim->format('Y-m-d H:i:s'),
            'tempo_inativo' => $this->tempo_inativo,
            'tempo_final' => $this->tempo_final
        ]);
    }

    /**
     * Método responsável excluir um usuário do banco de dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retornar a sessão pelo id
     * @param integer $id
     * @return SessionLogin
     */
    public static function getSessionLoginById(int $id)
    {
        return self::getSessionLogin('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar a sessão pelo token
     * @param string $token
     * @return SessionLogin|false|object|stdClass|null
     */
    public static function getSessionLoginByToken(string $token)
    {
        return self::getSessionLogin('token = "'.$token.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base no seu login
     * @param string $login
     * @return SessionLogin
     */
    public static function getSessionLoginByLogin(string $login)
    {
        return self::getSessionLogin('login_user = "'.$login.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string|null $where
     * @param string|null $order
     * @param string|null $group
     * @param string|null $limit
     */
    public static function getSessionLogin(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'): PDOStatement
    {
        return (new Database('tb_sessions_login'))->select($where,$order,$limit,$group,$fields);
    }
}