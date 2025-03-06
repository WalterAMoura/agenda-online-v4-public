<?php

namespace App\Controller\Api\V1;

use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\User;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\JWEDecrypt;
use App\Utils\JWEEncrypt;
use App\Utils\UUID;
use Exception;

class Auth extends Api
{
    /**
     * @param int $id
     * @param string $user
     * @param string $login
     * @param string $token
     * @param string $apikey
     * @param string $path
     * @return string
     */
    private static function genereteToken(int $id, string $user, string $login, string $token, string $apikey, string $path): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        $jwe = new JWEEncrypt($obOrganization->site,$obOrganization->short_name . ' - API Consumer', $id, $user, $login, $token,['apikey'=>$apikey,'path'=>$path]);
        return $jwe->getEncrypt();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function getToken(Request $request): mixed
    {
        //Post vars
        $postVars = $request->getPostVars();
        $senha = $postVars['password'];
        $username = $postVars['username'];
        $uuid = new UUID();
        $token = $uuid->getUUID();

        // recuperar headers
        $heardersVars = $request->getHeaders();
        $apikey = $heardersVars['x-api-key'];

        // recupera path
        $path = $request->getPathUser();

        // Valida campos vazios
        if(General::isNullOrEmpty($postVars['password']) or General::isNullOrEmpty($postVars['username'])){
            throw new Exception("Os campos 'username' e 'password' são obrigatórios.",400);
        }

        // Buscar usuário pelo username informado
        $obUser = User::getUserByUsername($username);


        if(!$obUser instanceof User){
            throw new Exception("Usuário ou senha são inválidos.",400);
        }


        // verifica se as senhas são as mesmas
        if(!password_verify($senha,$obUser->password)){
            throw new Exception("Usuário ou senha são inválidos.",400);
        }

        $id=$obUser->id;
        $user=$obUser->name;
        $login=$obUser->login;

        return [
            'token' => (string)self::genereteToken($id,$user,$login,$token,$apikey,$path)
        ];
    }

    /**
     * @param Request $request
     * @param string $token
     * @return mixed
     * @throws Exception
     */
    private static function decryptToken(Request $request, string $token): mixed
    {
        //Post vars
        $decrypt = new JWEDecrypt($token, $request);
        return (array)$decrypt->getDencrypt();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function checkToken(Request $request) : mixed
    {
        //Post vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if(!isset($postVars['token'])){
            throw new Exception("O campo 'token' é obrigatórios.",400);
        }

        $token = $postVars['token'];

        $decrypt = self::decryptToken($request, $token);

        $now = strtotime("now");

        if($now >= $decrypt['exp']){
            throw new Exception("Unauthorized", 401);
        }

        return [
            "message" => "Authorized"
        ];
    }
}