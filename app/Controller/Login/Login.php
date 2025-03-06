<?php

namespace App\Controller\Login;

use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SessionLogin as EntitySessionLogin;
use App\Model\Entity\User;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\JWEDecrypt;
use App\Utils\JWEEncrypt;
use App\Utils\UUID;
use App\Utils\View;
use App\Session\Users\Login as SessionUsersLogin;
use Exception;

class Login extends Page
{
    /**
     * @param int $id
     * @param string $user
     * @param string $login
     * @param string $token
     * @param array $customScopes
     * @return string
     */
    private static function generateToken(int $id, string $user, string $login, string $token, array $customScopes): string
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        $jwe = new JWEEncrypt($obOrganization->full_name, $obOrganization->full_name, $id, $user, $login, $token, $customScopes);
        return $jwe->getEncrypt();
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
     * Método responsável por retornar a renderização da página de alert
     * @param Request $request
     * @param string|null $erroMessage
     * @param string $typeMessage
     * @return string
     * @throws Exception
     */
    public static function getLogin(Request $request, string $erroMessage = null, string $typeMessage = 'getError')
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //inicia variável decrypt
        $dataUser = '';

        //Status Login
        $status = !is_null($erroMessage) ? Alert::$typeMessage($erroMessage): self::getStatus($request);

        $jwe = $_COOKIE['remember'] ?? '';


        if(!General::isNullOrEmpty($_COOKIE['remember']??null)){
            $decrypt = self::decryptToken($request, $jwe);
            $dataUser = (array)$decrypt['customScopes'];
        }

        $user = $dataUser['username']?? null;
        $pass = $dataUser['password']?? null;
        $checked =(is_null($user) and is_null($pass))? null: 'checked';

        //Conteúdo da pagina de alert
        $content = View::render('login',[
            'status' => $status,
            'checked' => $checked,
            'user' => $user,
            'passwd' => $pass
        ]);

        return parent::getPage($obOrganization->full_name . ' | Login', $content);
    }

    /**
     * Método responsável por definir o alert do usuário
     * @param Request $request
     * @return string|void
     * @throws Exception
     */
    public static function setLogin(Request $request)
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Post vars
        $postVars = $request->getPostVars();
        $username = $postVars['username'] ?? '';
        $senha = $postVars['password'] ?? '';
        $remember = isset($postVars['remember']) ? 'on' : 'off';

        $obUser = User::getUserByLogin($username);

        if(!$obUser instanceof User){
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Usuário ou senha inválidos.',null,-1);
            return self::getLogin($request,'Usuário ou Senha inválidos.');
        }

        // verifica se as senhas são as mesmas
        if(!password_verify($senha,$obUser->password)){
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Usuário ou senha inválidos.',null,-1);
            return self::getLogin($request,'Usuário ou Senha inválidos.');
        }


        // verifica status do usuário diferente de ativo
        if($obUser->id_status != 1){
            if($obUser->id_status == 3){
                //registra log
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Usuário bloqueado no sistema.',null,$obUser->id);
                return self::getLogin($request,'Usuário bloqueado no sistema, procure o administrador do sistema.');
            }
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Usuário desativado no sistema, procure o administrador do sistema.',null,$obUser->id);
            return self::getLogin($request,'Usuário desativado no sistema, procure o administrador do sistema.');
        }

        $obSessionLogin=self::setSessionLogin($request, $obUser);

        // Cria a sessão de ‘alert’
        SessionUsersLogin::login($obUser, $obSessionLogin);

        if($remember == 'on'){
            $token = $obSessionLogin->token;
            $id=$obUser->id;
            $user=$obUser->name;
            $login=$obUser->login;

            $jwe= (string)self::generateToken($id,$user,$login,$token, [ 'username' => $username,'password' => $senha ]);
            $cookie_name = "remember";
            $cookie_value = $jwe;
            setcookie($cookie_name, $cookie_value, time() + (86400*30), $obOrganization->path_default); // 86400 = 1 day
        }else{
            $cookie_name = "remember";
            $cookie_value = null;
            setcookie($cookie_name, $cookie_value,time() - (86400*60), $obOrganization->path_default);
        }

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        // Redireciona o usuário para home do usuário ou página ativa
        if(isset($obUser->home_path)){
            //$request->getRouter()->redirect($obUser->home_path);
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Usuário logado com sucesso.',$session['usuario']['token'],$obUser->id);
            $path = self::getMainPath($obUser->id_nivel);
            $request->getRouter()->redirect($path);
        }else{
            //registra log
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Erro buscar path do usuário, entre em contato com administrador do sistema!',$session['usuario']['token'],$obUser->id);
            return self::getLogin($request,'Erro buscar path do usuário, entre em contato com administrador do sistema!');
        }

    }


    /**
     * @param int $levelId
     * @return string
     */
    private static function getMainPath(int $levelId)
    {
        // recupera modulos para o level
        $obAccessModules = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 1' ,null,1)->fetchObject(EntityAccessModules::class);

        // retorna o path do primeiro modulo habilitado
        return $obAccessModules->home_path . $obAccessModules->path_module;
    }

    /**
     * Método responsável por criar a sessão do usuário no banco de dados
     * @param Request $request
     * @param User $obUser
     * @return mixed
     * @throws Exception
     */
    private static function setSessionLogin(Request $request, User $obUser)
    {

        // variáveis do servidor
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? null;
        $remoteHost = $_SERVER['REMOTE_HOST'] ?? null;
        $remotePort = $_SERVER['REMOTE_PORT'] ?? null;

        $uuid = new UUID();
        $token = $uuid->getUUID();

        $obSessionLogin = new EntitySessionLogin();
        $obSessionLogin->token = $token;
        $obSessionLogin->id_user = $obUser->id;
        $obSessionLogin->login_user = $obUser->login;
        $obSessionLogin->name_user = $obUser->name;
        $obSessionLogin->user_agent = $userAgent;
        $obSessionLogin->remote_addr = $remoteAddr;
        $obSessionLogin->remote_host = $remoteHost;
        $obSessionLogin->remote_port = $remotePort;
        $obSessionLogin->tempo_inativo = 0;
        $obSessionLogin->tempo_final = 0;
        $obSessionLogin->cadastrar();

        return $obSessionLogin;
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request)
    {
        //QueryParams
        $queryParams = $request->getQueryParams();

        // Status existe
        if(!isset($queryParams['status'])) return null;

        //Mensagens de status
        switch ($queryParams['status']){
            case 'logout':
                return Alert::getWarning('Sessão encerrada por inatividade!');
                break;
            case 'log':
                return Alert::getError('Error!');
                break;
            case 's_updated_pass':
                return Alert::getSuccess('Senha atualizada com sucesso, use a nova senha cadastrada para realizar o login.');
                break;
        }
    }

    /**
     * Método responsável por deslogar o usuário
     * @param Request $request
     */
    public static function setLogout(Request $request)
    {

        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        // Destoi a sessão de alert
        SessionUsersLogin::logout($session['usuario']['pathMain']);

        // Redireciona o usuário para a pagina de login
        $request->getRouter()->redirect('/login');

    }

    /**
     * Método responsável por deslogar o usuário por inatividade
     * @param Request $request
     * @return void
     */
    public static function setAutoLogout(Request $request)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        // Destoi a sessão de alert
        SessionUsersLogin::logout($session['usuario']['pathMain']);

        // Redireciona o usuário para a pagina de login
        $request->getRouter()->redirect('/logout?status=logout');
    }
}