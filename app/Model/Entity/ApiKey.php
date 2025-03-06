<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use DateTimeZone;
use Exception;
use PDOStatement;

class ApiKey
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private string $tabela = 'tb_apis';

    /**
     * Id da chave APIKEY
     * @var integer
     */
    public int $id;

    /**
     * Usuário associado a APIKEY
     * @var integer
     */
    public int $user_id;

    /**
     * Valor da chave APIKEY
     * @var string
     */
    public string $api_key;

    /**
     * Nome da chave APIKEY
     * @var string
     */
    public string $api_name;

    /**
     * Descrição da chave APIKEY
     * @var string
     */
    public string $api_description;

    /**
     * PATH acesso da chave APIKEY
     * @var string
     */
    public string $api_path;


    /**
     * Ativa/Inativa APIKEY
     * @var integer
     */
    public int $status_id;

    /**
     * Descrição Status Ativa/Inativa APIKEY
     * @var string
     */
    public string $status_description;

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
            'user_id' => $this->user_id,
            'api_key' => $this->api_key,
            'api_name' => $this->api_name,
            'api_description' => $this->api_description,
            'api_path' => $this->api_path,
            'status_id' => $this->status_id,
            'active'=> $this->status_id
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
            'user_id' => $this->user_id,
            'api_key' => $this->api_key,
            'api_name' => $this->api_name,
            'api_description' => $this->api_description,
            'api_path' => $this->api_path,
            'status_id' => $this->status_id,
            'active'=> $this->status_id
        ]);
    }

    /**
     * Método responsável remover um usuário do banco de dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retornar a apikey
     * @param int $id
     * @return ApiKey
     */
    public static function getApikeyById(int $id)
    {
        return self::getApiKey('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar a apikey
     * @param string $apikey
     * @return ApiKey
     */
    public static function getApikeyByKey($apikey)
    {
        return self::getApiKey('api_key = "'.$apikey.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar a apikey
     * @param string $apikey
     * @param string $path
     * @return ApiKey
     */
    public static function getApikeyByKeyByPath(string $apikey, string $path)
    {
        return self::getApiKey('api_key = "'.$apikey.'" AND api_path = "'.$path.'"')->fetchObject(self::class);
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
    public static function getApiKey(string $where = null, string $order = null, string $limit = null, string $group = null, string $fields = '*'):PDOStatement
    {
        return (new Database('cnt_apis'))->select($where,$order,$limit,$group,$fields);
    }
}