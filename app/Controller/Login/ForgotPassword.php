<?php

namespace App\Controller\Login;

use App\Controller\Email\ActiveAccount;
use App\Controller\Email\Alert;
use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\ActiveAccountUsers as EntityActiveAccountUsers;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\PasswordTemp as EntityPasswordTemp;
use App\Model\Entity\SessionLogin as EntitySessionLogin;
use App\Model\Entity\SettingsSmtp as EntitySettingsSmtp;
use App\Model\Entity\User as EntityUser;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\GeneratePassword;
use App\Utils\JWEDecrypt;
use App\Utils\JWEEncrypt;
use App\Utils\UUID;
use App\Utils\View;
use App\Session\Users\Login as SessionUsersLogin;
use DateTime;
use DateTimeZone;
use Exception;

class ForgotPassword extends Page
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
     */
    public static function getForgotPassword(Request $request, string $erroMessage = null, string $typeMessage = 'getError')
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Status Login
        $status = !is_null($erroMessage) ? Alert::$typeMessage($erroMessage): self::getStatus($request);

        //Conteúdo da pagina de alert
        $content = View::render('forgot-password',[
            'status' => $status
        ]);

        return parent::getPage($obOrganization->full_name . ' | Recuperar Senha', $content);
    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function setForgotPassword(Request $request)
    {
        //Debug::debug($request->getPostVars());
        // recupera as variaveis posts
        $postVars = $request->getPostVars();
        $email = $postVars['email'];

        // valida se o e-mail existe
        $obUser = EntityUser::getUserByUsername($email);

        if(!$obUser instanceof EntityUser){
            return self::getForgotPassword($request,'O e-mail informado ['.$email.'], não é valido, revise os dados e tente novamante.','getWarning');
        }

        // gera uma senha randomica
        $senha = GeneratePassword::generatePassword(16);

        // instancia de senha temporária
        $obPasswordTemp = new EntityPasswordTemp();
        $obPasswordTemp->password=$senha;
        $obPasswordTemp->id_user = $obUser->id;
        $obPasswordTemp->cadastrar();

        // envia email para ativação da conta
        self::sendEmailActiveAccount($request,$obUser->id,$obUser->name,$email);

        return self::getForgotPassword($request,'Enviamos para o e-mail ['.$email.'], as instruções para cadastrar uma nova senha.','getSuccess');
    }

    /**
     * @param Request $request
     * @param int $idUser
     * @param string $nameUser
     * @param string $emailUser
     * @return boolean
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    private static function sendEmailActiveAccount(Request $request, int $idUser, string $nameUser, string $emailUser): bool
    {
        $obSettingSmtp = EntitySettingsSmtp::getSettingsSmtpActive();

        $obApikey = EntityApikey::getApikeyById($obSettingSmtp->id_apikey);


        $uuid = new UUID();
        $token = $uuid->getUUID();

        $jwe = self::getToken($idUser,$nameUser,$emailUser,$token, $obApikey->api_key, '/email/set-new-password');

        $urlActive = URL . '/email/set-new-password?token='. $jwe;

        $success=\App\Controller\Email\ForgotPassword::sendEmailActiveAccount($emailUser,$nameUser, $urlActive);

        if($success === true){
            $expirationDate = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            $expirationDate->modify('+15 minutes');
            $expirationDate = $expirationDate->setTimezone(new DateTimeZone('UTC'));

            $obActiveAccountUsers = new EntityActiveAccountUsers();
            $obActiveAccountUsers->id_user = $idUser;
            $obActiveAccountUsers->token = $jwe;
            $obActiveAccountUsers->expiration_at = $expirationDate->format('Y-m-d H:i:s');
            $obActiveAccountUsers->status_token = 2;
            $obActiveAccountUsers->cadastrar();
        }

        return $success;

    }

    /**
     * @param int $id
     * @param string $user
     * @param string $login
     * @param string $token
     * @param string $apikey
     * @param string $path
     * @return mixed
     */
    private static function getToken(int $id, string $user, string $login, string $token, string $apikey, string $path): mixed
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        $jwe = new JWEEncrypt($obOrganization->site,$obOrganization->short_name . ' - Active Account', $id, $user, $login, $token, ['apikey'=>$apikey,'path'=>$path],1200);

        return (string)$jwe->getEncrypt();
    }

    /**
     *  Método responsável por renderizar a view success active account
     * @return string
     * @throws Exception
     */
    private static function getFailActiveAccount(): string
    {
        // Retorna a pagina completa
        return View::render('email/content/fail-active-account',[
            'title' => 'Tempo Expirado - Solicitação de Novo Token',
            'message_1' => 'O tempo para ativação da sua conta expirou.',
            'message_2' => 'Por favor, informe seu endereço de e-mail abaixo para receber um novo token para cadastrar sua senha.',
            'imgSignature' => URL . '/lib/img/favicon.png'
        ]);
    }

    /**
     * Método responsável por retornar a renderização da página de alert
     * @param Request $request
     * @param string|null $erroMessage
     * @param string $typeMessage
     * @return string
     * @throws Exception
     */
    public static function getNewPassword(Request $request, string $erroMessage = null, string $typeMessage = 'getError')
    {
        // recupera path da url
        $path=($request->getRouter()->getPathMain());

        // recupera token da URL
        $queryParams = $request->getQueryParams();
        $token = $queryParams['token'];

        // realiza o decode do token
        $tokenDecrypt=self::checkToken($token, $request);
        //Debug::debug($tokenDecrypt);
        if($tokenDecrypt['status'] === 'unauthorized' || $tokenDecrypt['decrypt']['customScopes']->path !== $path){
            // retorna para tela de solicitar e-mail
            $request->getRouter()->redirect('/forgot-password?status=expired_token');
        }

        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Status Login
        $status = !is_null($erroMessage) ? Alert::$typeMessage($erroMessage): self::getStatus($request);

        //Conteúdo da pagina de alert
        $content = View::render('email/content/set-new-password',[
            'imgSignature' => URL . '/lib/img/favicon.png',
            'status' => $status
        ]);

        return parent::getPage($obOrganization->full_name . ' | Cadastrar Nova Senha', $content);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function setNewPassword(Request $request): mixed
    {
        // recupera token da URL
        $queryParams = $request->getQueryParams();
        $token = $queryParams['token'];

        // decrypt token
        $decryptToken = self::decryptToken($request, $token);

        $postVars = $request->getPostVars();
        $senha = $postVars['nova-senha'] ?? '';

        if(General::isNullOrEmpty($postVars['nova-senha'])){
            return self::getNewPassword($request,'Senha vazia.','getError');
        }

        $obUser = new EntityUser();
        $obUser->id = $decryptToken['id'];
        $obUser->password = password_hash($senha, PASSWORD_DEFAULT, OPTIONS_BCRYPT);
        $obUser->updatePasswd();

        $request->getRouter()->redirect('/login?status=s_updated_pass');

        return [ 'success' => true ];

    }

    /**
     * @param string $token
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private static function checkToken(string $token, Request $request) : mixed
    {

        $decrypt = self::decryptToken($request, $token);

        $now = strtotime("now");

        if(!isset($decrypt['iss'])){
            return [ 'status' => 'falha' ];
        }

        if($now >= $decrypt['exp']){
            return [ 'status' => 'expired' ];
        }

        return [ 'status' => 'authorized', 'decrypt' => $decrypt ];
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
            case 'expired_token':
                return Alert::getWarning('Este token está expirado, informe novamente o e-mail utilizado para receber um novo token.');
                break;
        }
    }
}