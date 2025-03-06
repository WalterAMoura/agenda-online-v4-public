<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class SoundTeam
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_sound_team';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Usuário vinculado
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
     * Nome do membro da equipe de som
     * @var string
     */
    public $name;

    /**
     * Nome completo do membro da equipe de som
     * @var string
     */
    public $complete_name;

    /**
     * Contato
     * @var string
     */
    public $contato;

    /**
     * E-mail
     * @var string
     */
    public $email;

    /**
     * Contato do ancião com mascára
     * @var string
     */
    public $phone_mask;

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
            'email' => $this->email,
            'user_id' => $this->linked_user_id,
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
            'email' => $this->email,
            'user_id' => $this->linked_user_id,
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Método responsável excluir dados no banco dados no banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database($this->tabela))->delete('id = '. $this->id);
    }

    /**
     * Método responsável por retonar pelo ID
     * @param integer $id
     * @return SoundTeam
     */
    public static function getSoundTeamById($id)
    {
        return self::getSoundTeam('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar pelo ID
     * @param integer $id
     * @return SoundTeam
     */
    public static function getSoundTeamByLinkedUserId($id)
    {
        return self::getSoundTeam('linked_user_id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um membro pelo nome
     * @param string $name
     * @return SoundTeam
     */
    public static function getSoundTeamByName($name)
    {
        return self::getSoundTeam('name = "'.$name.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getSoundTeam($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('cnt_sound_team'))->select($where,$order,$limit,$group,$fields);
    }
}