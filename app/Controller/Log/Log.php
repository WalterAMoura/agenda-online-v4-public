<?php

namespace App\Controller\Log;

use App\Http\Request;
use App\Model\Entity\Logs as EntityLogs;
use App\Utils\Debug;
use App\Utils\JWEDecrypt;
use App\Utils\UUID;
use Exception;

class Log
{
    /**
     * Método responsável por gravar os dados de log no banco de dados
     * @param Request $request
     * @param int $idUser
     * @param string $class
     * @param string $data
     * @param string|null $token
     * @return array
     * @throws Exception
     */
    public static function setLog(Request $request, int $idUser, string $class, string $data, string $token = null)
    {
        // realiza o decode do token
        $uuid = new UUID();
        //Debug::debug($token);
        $tokenDecrypt= is_null($token)?[ 'decrypt' => ['token' => $uuid->getUUID() ] ] : self::checkToken($token, $request);
        // cria instancia
        $obLogs = new EntityLogs();
        $obLogs->id_user = $idUser;
        $obLogs->application = $class;
        $obLogs->data = $data;
        $obLogs->token = $tokenDecrypt['decrypt']['token'];
        $obLogs->cadastrar();
        return [ 'success' => true ];
    }

    /**
     * Método responsável por realizar o decrypt do token
     * @param string $token
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    private static function decryptToken(string $token, Request $request): mixed
    {
        //Post vars
        $decrypt = new JWEDecrypt($token, $request);
        return (array)$decrypt->getDencrypt();
    }

    /**
     * Método responsável por checar o token
     * @param string $token
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private static function checkToken(string $token, Request $request) : mixed
    {

        $decrypt = self::decryptToken($token, $request);
//
//        $now = strtotime("now");
//
//        if(!isset($decrypt['iss'])){
//            return [ 'status' => 'falha' ];
//        }
//
//        if($now >= $decrypt['exp']){
//            return [ 'status' => 'expired' ];
//        }

        return [ 'status' => 'authorized', 'decrypt' => $decrypt ];
    }
}