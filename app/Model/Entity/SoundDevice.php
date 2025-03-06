<?php

namespace App\Model\Entity;

use App\Model\Database\Database;
use DateTime;
use Exception;
use PDOStatement;

class SoundDevice
{
    /**
     * Tabela usada pela classe
     * @var string
     */
    private $tabela = 'tb_sound_device';
    /**
     * ‘ID’ do registro
     * @var integer
     */
    public $id;

    /**
     * Nome do equipamento de som
     * @var string
     */
    public $device;

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
            'device' => $this->device,
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
            'device' => $this->device,
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
     * @return SoundDevice
     */
    public static function getSoundDeviceById(int $id)
    {
        return self::getSoundDevice('id = '.$id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um membro pelo nome
     * @param string $device
     * @return SoundDevice
     */
    public static function getSoundDeviceByName(string $device)
    {
        return self::getSoundDevice('device = "'.$device.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar os departamentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $group
     * @param string $fields
     */
    public static function getSoundDevice($where = null, $order = null, $limit = null, $group = null, $fields = '*'):PDOStatement
    {
        return (new Database('tb_sound_device'))->select($where,$order,$limit,$group,$fields);
    }
}