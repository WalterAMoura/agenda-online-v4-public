<?php

namespace App\Controller\Application;

use App\Controller\Email\ActiveAccount;
use App\Http\Request;
use App\Model\Entity\AccessModules as EntityAccessModules;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SettingsSmtp as EntitySettingsSmtp;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\Level as EntityLevel;
use App\Model\Entity\StatusUser as EntityStatusUser;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Model\Entity\PasswordTemp as EntityPasswordTemp;
use App\Utils\Debug;
use App\Utils\General;
use App\Utils\GeneratePassword;
use App\Utils\JWEEncrypt;
use App\Utils\Pagination;
use App\Utils\PasswordEncryptor;
use App\Utils\UUID;
use App\Utils\View;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\ActiveAccountUsers as EntityActiveAccountUsers;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class User extends Page
{
    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @returm string
     */
    private static function getNivelItems($request, &$obPagination, $selected = null)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        $levelId = $session['usuario']['nivel'];

        //SELECT access_level.id as level_id,access_level.created_at, access_level.description, access_level.home_path, access_level.level, access_level.updated_at, CASE WHEN access_level.id = subquery.id THEN 0 ELSE 1 END AS order_level  FROM (SELECT id FROM tb_access_level WHERE id = 1) subquery, `tb_access_level` as access_level WHERE access_level.id > 0 ORDER BY order_level
        $options = '';
        $order = isset($selected)? 'order_level' : null;
        $where = 'id >= ' . $levelId;
        $query = isset($selected)? 'id, created_at, description, home_path, level, updated_at, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_level' : '*';
        $results = EntityLevel::getLevels($where, $order,null,null,$query);
        while ($obLevel = $results->fetchObject(EntityLevel::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obLevel->id,
                'optionName' => $obLevel->description
            ]);
        }

        // retorna os options levels
        return $options;

    }

    /**
     * Método responsável por obter a renderização dos items de usuários para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @returm string
     */
    private static function getStatusUsersItems($request, &$obPagination, $selected = null)
    {

        // recupera status users
        $options = '';
        $order = isset($selected)? 'order_status' : null;
        $where = 'status <> "DELETED"';
        $query = isset($selected)? 'id, created_at, status, updated_at, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityStatusUser::getStatusUser($where, $order,null,null, $query);
        while ($obStatusUser = $results->fetchObject(EntityStatusUser::class)){
            $options .= View::render('application/modules/advanced-settings/forms/select',[
                'optionValue' => $obStatusUser->id,
                'optionName' => $obStatusUser->status
            ]);
        }

        // retorna os options levels
        return $options;

    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function getNewUser($request)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/users',[
            'title' => 'Cadastrar Usuário',
            'breadcrumbItem' => 'Cadastrar Usuário',
            'options' => self::getNivelItems($request, $obPagination),
            'optionsStatusUser' => self::getStatusUsersItems($request, $obPagination),
            'nome' => null,
            'email' => null,
            'login' => null,
            'visible' => 'd-none',
            'required' => 'required',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de usuários.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Users',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function setNewUser(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $login = $postVars['login'] ?? '';
        $level = $postVars['level'] ?? '';
        $statusUser = $postVars['statusUser'] ?? '';

        // valida o email do usuário
        $obUserEmail = EntityUser::getUserByEmail($email);
        if($obUserEmail instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users/new?status=duplicated');
        }

        // valida o login do usuário
        $obUserLogin = EntityUser::getUserByLogin($login);
        if($obUserLogin instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users/new?status=duplicated');
        }

        $senha = GeneratePassword::generatePassword(16);

        // Nova instancia de usuários
        $obUser = new EntityUser();
        $obUser->name = $nome;
        $obUser->email = $email;
        $obUser->id_nivel = $level;
        $obUser->login = $login;
        $obUser->id_status = $statusUser;
        $obUser->password = password_hash(GeneratePassword::decryptPassword($senha), PASSWORD_DEFAULT, OPTIONS_BCRYPT);
        $obUser->cadastrar();

        // instancia de senha temporária
        $obPasswordTemp = new EntityPasswordTemp();
        $obPasswordTemp->password=$senha;
        $obPasswordTemp->id_user = $obUser->id;
        $obPasswordTemp->cadastrar();

        // envia e-mail para ativação da conta
        self::sendEmailActiveAccount($request,$obUser->id,$nome,$email);


        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/users/'.$obUser->id.'/edit?status=created');
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

        $jwe = self::getToken($idUser,$nameUser,$emailUser,$token, $obApikey->api_key, '/email/active-account');

        $urlActive = URL . '/email/active-account?token='. $jwe;

        $success=ActiveAccount::sendEmailActiveAccount($emailUser,$nameUser, $urlActive);

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
            case 'created':
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail ou login digitado já sendo usado por outro usuário!');
                break;
            case 'failed':
                return Alert::getError('Você não pode excluir seu próprio usuário!');
                break;
        }
    }


    /**
     * Método responsável por retornar o formulário de edição de um novo usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getEditUser($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        // Obtém o depoimento do banco de dados
        $obUser = EntityUser::getUserById($id);
        // Valida instância
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }


        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/users',[
            'title' => 'Editar Usuário',
            'breadcrumbItem' => 'Editar Usuário',
            'nome' => $obUser->name,
            'email' => $obUser->email,
            'login' => $obUser->login,
            'level' => $obUser->id_nivel,
            'required' => null,
            'visible' => 'd-none',
            'options' => self::getNivelItems($request, $obPagination, $obUser->id_nivel),
            'optionsStatusUser' => self::getStatusUsersItems($request, $obPagination, $obUser->id_status),
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de usuários.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Users',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setEditUser($request,$id)
    {
        // Obtém o usuário do banco de dados
        $obUser = EntityUser::getUserById($id);


        // Valida instância
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }

        //Post vars
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        //$senha = $postVars['senha'] ?? '';
        $login = $postVars['login'] ?? '';
        $level = $postVars['level'] ?? '';
        $statusUser = $postVars['statusUser'] ?? '';

        // valida o email do usuário
        $obUserEmail = EntityUser::getUserByEmail($email);
        if($obUserEmail instanceof EntityUser && $obUserEmail->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/users/'.$id.'/edit?status=duplicated');
        }

        // valida o login do usuário
        $obUserLogin = EntityUser::getUserByLogin($login);
        if($obUserLogin instanceof EntityUser && $obUserLogin->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/users/'.$id.'/edit?status=duplicated');
        }

        $obUser = new EntityUser();
        $obUser->id = $id;
        $obUser->name = $nome;
        $obUser->email = $email;
        $obUser->id_nivel = $level;
        $obUser->login = $login;
        $obUser->id_status = $statusUser;
        //$obUser->password = $obUserLogin->password;
        $obUser->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/users/'.$obUser->id.'/edit?status=updated');
    }

    /**
     * Método responsável por verificar se o usuário excluído é o mesmo logado
     * @param integer $id
     * @return boolean
     */
    private static function isTheSameUserSession($id)
    {
        return SessionUsersLogin::isTheSameUser($id);
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     * @throws Exception
     */
    public static function getDeleteUser($request,$id)
    {
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        if(self::isTheSameUserSession($id)){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }
        // Obtém o depoimento do banco de dados
        $obUser= EntityUser::getUserById($id);

        // Valida instância
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }
        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/users',[
            'title' => 'Excluir Usuário',
            'breadcrumbItem' => 'Excluir Usuário',
            'nome' => $obUser->name,
            'email' => $obUser->email,
            'login' => $obUser->login
        ]);

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Users',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return void
     * @throws Exception
     */
    public static function setDeleteUser($request,$id)
    {
        // Obtém o depoimento do banco de dados
        $obUser= EntityUser::getUserById($id);

        // Valida instância
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }

        $login = $obUser->login;
        $email = $obUser->email;

        $obStatusUser = EntityStatusUser::getStatusUserByName('DELETED');

        // Excluir o usuário
        $obUser = new EntityUser();
        $obUser->id = $id;
        $obUser->id_status = $obStatusUser->id??4;
        $obUser->login = 'DELETED_' . $login;
        $obUser->email = 'DELETED_' . $email;
        $obUser->updateDeleted();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/users?status=deleted');

    }

    /**
     * @param int $levelId
     * @return array
     */
    private static function getMainPath(int $levelId)
    {
        // recupera módulos para o level
        $obAccessModules = EntityAccessModules::getAccessModules('level_id = '. $levelId . ' AND type_id_module = 1' ,null,1)->fetchObject(EntityAccessModules::class);

        // retorna o path do primeiro modulo habilitado
        return [ 'path' => $obAccessModules->home_path . $obAccessModules->path_module, 'label' => $obAccessModules->label ];
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function setPasswd(Request $request): mixed
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();

        $postVars = $request->getPostVars();
        $senha = $postVars['nova-senha'] ?? '';

        if(General::isNullOrEmpty($postVars['nova-senha'])){
            $request->getRouter()->redirect($session['usuario']['pathMain'].'?status=failed');
        }

        $obUser = new EntityUser();
        $obUser->id = $session['usuario']['id'];
        $obUser->password = password_hash($senha, PASSWORD_DEFAULT, OPTIONS_BCRYPT);
        $obUser->updatePasswd();

        $session=SessionUsersLogin::getDataSession();
        $username = $session['usuario']['nome']?? 'Conta';

        $ret = self::getMainPath($session['usuario']['nivel']);

        $path = $ret['path'];

        $request->getRouter()->redirect($path.'?status=updated-pwd');

        return true;

    }

    /**
     * Método responsável por resetar a senha do usuário
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public static function setResetPasswd(Request $request, int  $id): mixed
    {
        // Obtém o depoimento do banco de dados
        $obUser= EntityUser::getUserById($id);

        // Valida instância
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }

        $senha = "123456";
        $obUser = new EntityUser();
        $obUser->id = $id;
        $obUser->password = password_hash($senha, PASSWORD_DEFAULT, OPTIONS_BCRYPT);
        $obUser->updatePasswd();

        //Debug::debug($obUser);
        $request->getRouter()->redirect('/application/advanced-settings/users?status=reset-pwd');

        return true;

    }

    /**
     * Método responsável por reenviar a ativação da conta
     * @param Request $request
     * @param int $id
     * @return mixed
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function resendAccountActivate(Request $request, int $id): mixed
    {
        // Obtém o depoimento do banco de dados
        $obUser= EntityUser::getUserById($id);

        // Valida instância
        if(!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/users?status=failed');
        }

        // envia e-mail para ativação da conta
        self::sendEmailActiveAccount($request,$obUser->id,$obUser->name,$obUser->email);

        //Debug::debug($obUser);
        $request->getRouter()->redirect('/application/advanced-settings/users?status=resend-account-activated');

        return true;

    }
}