<?php

namespace App\Controller\Application;

use App\Controller\Email\Register;
use App\Http\Request;
use App\Model\Entity\Departaments as EntityDepartments;
use App\Model\Entity\Organization as EntityOrganization;
use App\Model\Entity\SettingsSmtp as EntitySettingsSmtp;
use App\Model\Entity\User as EntityUser;
use App\Model\Entity\TempUser as EntityTempUser;
use App\Model\Entity\Level as EntityLevel;
use App\Model\Entity\StatusUser as EntityStatusUser;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Model\Entity\PasswordTempUsers as EntityPasswordTemp;
use App\Utils\Debug;
use App\Utils\GeneratePassword;
use App\Utils\JWEEncrypt;
use App\Utils\Pagination;
use App\Utils\UUID;
use App\Utils\View;
use App\Session\Users\Login as SessionUsersLogin;
use App\Model\Entity\ActiveAccountUsers as EntityActiveAccountUsers;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;


class TempUser extends Page
{

    /**
     * @param string|null $selected
     * @return string
     */
    private static function getDepartments(string $selected = null)
    {
        // status agendamento
        $options = '';
        $order = isset($selected)? 'order_status' : 'department ASC';
        $where = 'id > 1 or id = 0';
        $query = isset($selected)? 'id, department, CASE WHEN id = '.$selected.' THEN 0 ELSE 1 END AS order_status' : '*';
        $results = EntityDepartments::getDepartments($where,$order,null,null,$query);
        while ($obDepartment = $results->fetchObject(EntityDepartments::class)){
            $options .= View::render('select',[
                'optionValue' => $obDepartment->id,
                'optionName' => $obDepartment->department
            ]);
        }

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
        $content = View::render('application/modules/advanced-settings/forms/temp-users',[
            'title' => 'Cadastrar Usuário Temp',
            'breadcrumbItem' => 'Cadastrar Usuário Temp',
            'departments' => self::getDepartments(0),
            'name' => null,
            'email' => null,
            'login' => null,
            'btnType' => 'info',
            'btnName' => 'Salvar',
            'readonly' => null,
            'disabled' => null,
            'type' => 'submit',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de usuários temp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Temp Users',$content,'advanced-settings');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function setNewUser(Request $request)
    {
        //PostVars
        $postVars = $request->getPostVars();

        $fullName = $postVars['name'];
        $email = $postVars['email'];
        $departmentId = $postVars['department'];
        $login = $postVars['login'];
        $statusUser = 2;

        // valida o email do usuário
        $obUserEmail = EntityUser::getUserByEmail($email);
        if($obUserEmail instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/new?status=duplicated');
        }

        // valida o login do usuário
        $obUserLogin = EntityUser::getUserByLogin($login);
        if($obUserLogin instanceof EntityUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/new?status=duplicated');
        }

        // valida o email do usuário
        $obTempUserEmail = EntityTempUser::getUserByEmail($email);
        if($obTempUserEmail instanceof EntityTempUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/new?status=duplicated');
        }

        // valida o login do usuário
        $obTempUserLogin = EntityTempUser::getTempUserByLogin($login);
        if($obTempUserLogin instanceof EntityTempUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/new?status=duplicated');
        }

        $senha = GeneratePassword::generatePassword(16);

        // Nova instancia de usuários
        $obTempUser = new EntityTempUser();
        $obTempUser->name = $fullName;
        $obTempUser->email = $email;
        $obTempUser->department_id = $departmentId;
        $obTempUser->login = $login;
        $obTempUser->id_status = $statusUser;
        $obTempUser->password = password_hash(GeneratePassword::decryptPassword($senha), PASSWORD_DEFAULT, OPTIONS_BCRYPT);
        $obTempUser->cadastrar();

        // instancia de senha temporária
        $obPasswordTemp = new EntityPasswordTemp();
        $obPasswordTemp->password=$senha;
        $obPasswordTemp->id_user = $obTempUser->id;
        $obPasswordTemp->cadastrar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$obTempUser->id.'/edit?status=created');

        return [ 'success' => true ];
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

        $urlActive = URL . '/email/confirmed-register?token='. $jwe;

        $success=Register::sendEmailActiveAccount($emailUser,$nameUser, $urlActive);

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
     * @param Request $request
     * @param int $idUser
     * @param string $nameUser
     * @param string $emailUser
     * @return boolean
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    private static function sendEmailReprovedAccount(Request $request, int $idUser, string $nameUser, string $emailUser): bool
    {
        return Register::sendEmailReprovedAccount($emailUser,$nameUser, '');
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
            case 'approved':
                return Alert::getSuccess('Usuário aprovado com sucesso!');
                break;
            case 'reproved':
                return Alert::getWarning('Usuário reprovado!');
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
        $obTempUser = EntityTempUser::getUserById($id);
        // Valida instância
        if(!$obTempUser instanceof EntityTempUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users?status=failed');
        }


        //Conteúdo do formulário

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/forms/temp-users',[
            'title' => 'Editar Usuário Temp',
            'breadcrumbItem' => 'Editar Usuário Temp',
            'departments' => self::getDepartments($obTempUser->department_id),
            'name' => $obTempUser->name,
            'email' => $obTempUser->email,
            'login' => $obTempUser->login,
            'btnType' => 'success',
            'btnName' => 'Atualizar',
            'readonly' => ($obTempUser->id_status == 1 || $obTempUser->id_status == 5)? 'readonly' : null,
            'disabled' => ($obTempUser->id_status == 1 || $obTempUser->id_status == 5)? 'disabled' : null,
            'type' => ($obTempUser->id_status == 1 || $obTempUser->id_status == 5)? 'button' : 'submit',
            'status' => self::getStatus($request)
        ]);

        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Acesso a página de usuários temp.');

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Temp Users',$content,'advanced-settings');
    }

    /**
     * Método responsável por gravar a atualização de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setEditUser($request,$id)
    {
        // Obtém o usuário do banco de dados
        $obUser = EntityTempUser::getUserById($id);


        // Valida instância
        if(!$obUser instanceof EntityTempUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users?status=failed');
        }

        //Post vars
        $postVars = $request->getPostVars();
        $fullName = $postVars['name'];
        $email = $postVars['email'];
        $departmentId = $postVars['department'];
        $login = $postVars['login'];
        $password = $obUser->password;
        $statusUser = $obUser->id_status;

        // valida o email do usuário
        $obUserEmail = EntityUser::getUserByEmail($email);
        if($obUserEmail instanceof EntityUser and $obUserEmail != $id){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$obUser->id.'/edit?status=duplicated');
        }

        // valida o login do usuário
        $obUserLogin = EntityUser::getUserByLogin($login);
        if($obUserLogin instanceof EntityUser and $obUserLogin != $id){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$obUser->id.'/edit?status=duplicated');
        }

        // valida o email do usuário
        $obTempUserEmail = EntityTempUser::getUserByEmail($email);
        if($obTempUserEmail instanceof EntityTempUser and $obTempUserEmail->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$obUser->id.'/edit?status=duplicated');
        }

        // valida o login do usuário
        $obTempUserLogin = EntityTempUser::getTempUserByLogin($login);
        if($obTempUserLogin instanceof EntityTempUser and $obTempUserLogin->id != $id){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$obUser->id.'/edit?status=duplicated');
        }

        // Nova instancia de usuários
        $obTempUser = new EntityTempUser();
        $obTempUser->id = $id;
        $obTempUser->name = $fullName;
        $obTempUser->email = $email;
        $obTempUser->department_id = $departmentId;
        $obTempUser->login = $login;
        $obTempUser->id_status = $statusUser;
        $obTempUser->password = $password;
        $obTempUser->atualizar();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$obTempUser->id.'/edit?status=updated');

        return [ 'success' => true ];
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
            $request->getRouter()->redirect('/application/advanced-settings/temp-users?status=failed');
        }
        // Obtém o depoimento do banco de dados
        $obTempUser= EntityTempUser::getUserById($id);

        // Valida instância
        if(!$obTempUser instanceof EntityTempUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users?status=failed');
        }

        //Conteúdo do formulário
        $content = View::render('application/modules/advanced-settings/delete/temp-users',[
            'title' => 'Excluir Usuário',
            'breadcrumbItem' => 'Excluir Usuário',
            'name' => $obTempUser->name,
            'email' => $obTempUser->email,
            'login' => $obTempUser->login
        ]);

        // Retorna a pagina completa
        return parent::getPanel($obOrganization->full_name . ' | Temp Users',$content,'advanced-settings');
    }

    /**
     * Método responsável por excluir de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setDeleteUser($request,$id)
    {
        // Obtém o depoimento do banco de dados
        $obTempUser= EntityTempUser::getUserById($id);

        // Valida instância
        if(!$obTempUser instanceof EntityTempUser){
            $request->getRouter()->redirect('/application/advanced-settings/temp-users?status=failed');
        }

        $login = $obTempUser->login;
        $email = $obTempUser->email;

        $obStatusUser = EntityStatusUser::getStatusUserByName('DELETED');

        // Excluir o usuário
        $obTempUser = new EntityTempUser();
        $obTempUser->id = $id;
        $obTempUser->id_status = $obStatusUser->id??4;
        $obTempUser->login = 'DELETED_' . $login;
        $obTempUser->email = 'DELETED_' . $email;
        $obTempUser->updateDeleted();

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/temp-users?status=deleted');

        return [ 'success' => true ];

    }

    /**
     * Método responsável por aprovar o usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setApproved(Request $request, int $id)
    {
        //valida id
        $obTempUsers = EntityTempUser::getUserById($id);
        if(!$obTempUsers instanceof EntityTempUser){
            // Redireciona
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$id.'/edit?status=failed');
        }

        $obTempPassword = EntityPasswordTemp::getPasswordTempByUserId($obTempUsers->id);
        $password = ($obTempPassword instanceof EntityPasswordTemp)? password_hash(GeneratePassword::decryptPassword($obTempPassword->password), PASSWORD_DEFAULT,OPTIONS_BCRYPT ): $obTempUsers->password;

        $obUser = new EntityUser();
        $obUser->name = $obTempUsers->name;
        $obUser->email = $obTempUsers->email;
        $obUser->id_nivel = 12;
        $obUser->login = $obTempUsers->login;
        $obUser->id_status = $obTempUsers->id_status;
        $obUser->password = $password;
        $obUser->cadastrar();

        $obTempUsers = new EntityTempUser();
        $obTempUsers->id = $id;
        $obTempUsers->id_status = 1;
        $obTempUsers->user_id = $obUser->id;
        $obTempUsers->updateActiveted();

        // envia email para ativação da conta
        self::sendEmailActiveAccount($request,$obUser->id,$obUser->name,$obUser->email);

        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$id.'/edit?status=approved');

        return [ 'success' => true ];
    }

    /**
     * Método responsável por reprovar o usuário
     * @param Request $request
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public static function setReproved(Request $request, int $id)
    {
        //valida id
        $obTempUsers = EntityTempUser::getUserById($id);
        if(!$obTempUsers instanceof EntityTempUser){
            // Redireciona
            $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$id.'/edit?status=failed');
        }

        $login = $obTempUsers->login;
        $email = $obTempUsers->email;

        $_obTempUsers = new EntityTempUser();
        $_obTempUsers->id = $id;
        $_obTempUsers->login = 'REPROVED_' . $login;
        $_obTempUsers->email = 'REPROVED_' . $email;
        $_obTempUsers->id_status = 5;
        $_obTempUsers->user_id = 0;
        $_obTempUsers->updateReproved();

        // envia email de notificar o usuário
        self::sendEmailReprovedAccount($request,$obTempUsers->id,$obTempUsers->name,$obTempUsers->email);


        // Redireciona
        $request->getRouter()->redirect('/application/advanced-settings/temp-users/'.$id.'/edit?status=reproved');

        return [ 'success' => true ];
    }
}