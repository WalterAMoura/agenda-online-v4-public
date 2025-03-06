<?php

namespace App\Controller\Session;

use App\Http\Request;
use App\Model\Entity\Visitor as EntityVistor;
use App\Model\Entity\SessionLogin as EntitySessionLogin;
use App\Utils\Debug;
use App\Utils\JWEDecrypt;
use DateTime;
use DateTimeZone;
use Exception;
use App\Session\Users\Login as SessionUsersLogin;

class Session
{

    /**
     * Método responsável por zerar o tempo de inatividade do usuário
     * @param Request $request
     * @throws Exception
     * @return array
     */
    public  static function setZeroTime(Request $request)
    {
        $postVars = $request->getPostVars();

        $token = $postVars['token'];
        $type = $postVars['type'];

        //Valida os campos obrigatórios
        if(!isset($postVars['token'])){
            throw new Exception("O campo 'token' é obrigatórios.",400);
        }

        $decrypt = self::decryptToken($request, $token);

        $obSessionLogin = EntitySessionLogin::getSessionLoginByToken($decrypt['token']);
        if(!$obSessionLogin instanceof EntitySessionLogin){
            // recupera variáveis de sessão do usuário
            $session=SessionUsersLogin::getDataSession();

            // Destrói a sessão de alert
            SessionUsersLogin::logout($session['usuario']['pathMain']);

            // Redireciona o usuário para a pagina de login
            return [
                'path' => URL .'/auto-logout?status=logout',
                'success' => false
            ];
        }

        $_obSessionLogin = new EntitySessionLogin();
        $_obSessionLogin->id = $obSessionLogin->id;
        $_obSessionLogin->tempo_inativo = 0;
        $_obSessionLogin->tempo_final = $obSessionLogin->tempo_final;
        $_obSessionLogin->updateTime();

        return [
            'success' => true
        ];
    }

    /**
     * Método responsável por atualizar o tempo de inatividade do usuário
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setUpdateTime(Request $request)
    {
        $postVars = $request->getPostVars();

        $token = $postVars['token'];
        $type = $postVars['type'];

        //Valida os campos obrigatórios
        if(!isset($postVars['token'])){
            throw new Exception("O campo 'token' é obrigatórios.",400);
        }

        $decrypt = self::decryptToken($request, $token);

        $obSessionLogin = EntitySessionLogin::getSessionLoginByToken($decrypt['token']);
        if(!$obSessionLogin instanceof EntitySessionLogin){
            // recupera variáveis de sessão do usuário
            $session=SessionUsersLogin::getDataSession();

            // Destrói a sessão de alert
            SessionUsersLogin::logout($session['usuario']['pathMain']);

            // Redireciona o usuário para a pagina de login
            return [
                'path' => URL .'/auto-logout?status=logout',
                'success' => false
            ];
        }

        if($obSessionLogin->tempo_inativo >= SESSION_EXPIRATION){
            // recupera variáveis de sessão do usuário
            $session=SessionUsersLogin::getDataSession();

            // Destrói a sessão de alert
            SessionUsersLogin::logout($session['usuario']['pathMain']);

            // Redireciona o usuário para a pagina de login
            return [
                'path' => URL .'/auto-logout?status=logout',
                'success' => false
            ];
        }

        $_obSessionLogin = new EntitySessionLogin();
        $_obSessionLogin->id = $obSessionLogin->id;
        $_obSessionLogin->tempo_inativo = $obSessionLogin->tempo_inativo+1;
        $_obSessionLogin->tempo_final = $obSessionLogin->tempo_final+1;
        $_obSessionLogin->updateTime();

        return [
            'success' => true,
            'elapsedTime' => $obSessionLogin->tempo_inativo+1,
            'exp' => SESSION_EXPIRATION
        ];
    }

    /**
     * Método responsável por realizar o decrypt do token
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
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setOnlineGuest(Request $request)
    {
        // Recuperar variáveis post
        $postVars = $request->getPostVars();

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        $result = (array)self::getOnlineGuestByUser($session['usuario']['id'], $session['usuario']['login']);
        $type = (!empty($result['id']))? 'update': 'insert';

        $obVistors = new EntityVistor();

        if($type === 'update'){
            $obVistors->id = $result['id'];
            $obVistors->login = $result['login'];
            $obVistors->id_user = $result['id_user'];
            $obVistors->name_user = $result['name_user'];
            $obVistors->start_date = $result['start_date'];
            $obVistors->atualizar();
        }else{
            $obVistors->login = $session['usuario']['login'];
            $obVistors->id_user = $session['usuario']['id'];
            $obVistors->name_user = $session['usuario']['nome'];
            $obVistors->cadastrar();
        }

        SessionUsersLogin::setSessionVisitors($obVistors->id);

        $online = (array)self::getOnlineGuest();

        return [
            'success' => true
        ];
    }

    /**
     * Método responsável por retornar os usuários online
     * @param $id
     * @param $login
     * @return mixed
     * @throws Exception
     */
    private static function getOnlineGuestByUser($id, $login)
    {
        $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $now->setTimezone(new DateTimeZone("UTC"));
        $now = $now->format('Y-m-d');

        // Instancia de vistas
        $obVistors = EntityVistor::getVisitors('start_date > "'. $now .'" AND id_user ='. $id.' AND login ="'. $login.'"');

        return $obVistors->fetchObject(EntityVistor::class);
    }

    /**
     * Método responsável por retornar o número de usuários online
     * @return mixed
     * @throws Exception
     */
    private static function getOnlineGuest()
    {
        $data['atual'] = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $data['atual']->setTimezone(new \DateTimeZone("UTC"));
        $data['atual'] = $data['atual']->format('Y-m-d H:i:s');
        $data['online'] = strtotime($data['atual'] . " - 20 seconds");
        $data['online'] = date('Y-m-d H:i:s',$data['online']);

        // Instancia de vistas
        $obVistors = EntityVistor::getVisitors('end_date >= "'. $data['online'] .'"',null,null,null,'COUNT(id) AS online');

        return $obVistors->fetchObject(EntityVistor::class);
    }
}