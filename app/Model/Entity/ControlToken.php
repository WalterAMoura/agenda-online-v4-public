<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class ControlToken
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_control_token';

    /**
     * Id
     * @var integer
     */
    public int $id;

    /**
     * Hash Token
     * @var string
     */
    public int $hash_token;

    /**
     * Chave de criptografia
     * @var string
     */
    public string $encrypted_key;

    /**
     * Token
     * @var string
     */
    public string $token;

    /**
     * Data de criação
     * @var string|DateTime
     */
    public string|DateTime $created_at;

    /**
     * Data de atualização
     * @var string|DateTime
     */
    public string|DateTime $updated_at;

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
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'token' => $this->token,
            'encrypted_key' => $this->encrypted_key,
            'hash_token' => $this->hash_token
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
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'token' => $this->token,
            'encrypted_key' => $this->encrypted_key,
            'hash_token' => $this->hash_token
        ]);
    }

    /**
     * Método responsável por retornar uma instância pelo id da classe
     * @param int $id
     * @return ControlToken
     */
    public static function getControlTokenById(int $id)
    {
        return self::getControlToken('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma instância da classe pelo hashToken
     * @param $hashToken
     * @return ControlToken
     */
    public static function getControlTokenByHash($hashToken)
    {
        return self::getControlToken('hash_token = "'.$hashToken.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string|null $group
     * @param string $fields
     * @return PDOStatement
     */
    public static function getControlToken(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'):PDOStatement
    {
        return (new Database('tb_control_token'))->select($where,$order,$limit,$group,$fields);
    }
}