<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class AccessTokenWhatsApp
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_access_token_whatsapp';

    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Token ativação
     * @var string
     */
    public $graph_api_token;

    /**
     * Id do telefone
     * @var string
     */
    public $business_phone_number_id;

    /**
     * Nome do usuário
     * @var string
     */
    public $name_user;

    /**
     * Status token
     * @var integer
     */
    public $status_id;

    /**
     * Descrição do status token
     * @var string
     */
    public $status_description;

    /**
     * Data criação token
     * @var DateTime|string
     */
    public $created_at;

    /**
     * Expiração do token
     * @var DateTime|string
     */
    public $expiration_at;

    /**
     * Data de atualização token
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
            'business_phone_number_id' => $this->business_phone_number_id,
            'graph_api_token' => $this->graph_api_token,
            'expiration_at' => $this->expiration_at,
            'status_id' => $this->status_id,
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
            'business_phone_number_id' => $this->business_phone_number_id,
            'graph_api_token' => $this->graph_api_token,
            'expiration_at' => $this->expiration_at,
            'status_id' => $this->status_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável por atualizar os dados no banco
     * @return boolean
     * @throws Exception
     */
    public function updateStatusToken()
    {
        $dtUpdate = new DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $dtUpdate->setTimezone(new \DateTimeZone('UTC'));
        return (new Database($this->tabela))->update('id = '. $this->id,[
            'status_id' => $this->status_id,
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
     * Método responsável por retornar um mês pelo número
     * @param integer $idAccessTokenWhatsApp
     * @return AccessTokenWhatsApp
     */
    public static function getAccessTokenWhatsAppById(int $idAccessTokenWhatsApp)
    {
        return self::getAccessTokenWhatsApp('id = '.$idAccessTokenWhatsApp)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um mês pelo número
     * @param int $idStatusWhatsApp
     * @return AccessTokenWhatsApp
     */
    public static function getAccessTokenWhatsAppByStatusId(int $idStatusWhatsApp)
    {
        return self::getAccessTokenWhatsApp('status_id = '.$idStatusWhatsApp,' id DESC', 1)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um mês pelo número
     * @param string $token
     * @return AccessTokenWhatsApp
     */
    public static function getAccessTokenWhatsAppByToken(string $token)
    {
        return self::getAccessTokenWhatsApp('token = "'.$token.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os meses
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getAccessTokenWhatsApp($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_access_token_whatsapp'))->select($where,$order,$limit,$group,$fields);
    }
}