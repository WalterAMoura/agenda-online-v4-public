<?php

namespace App\Controller\Application;

use App\Controller\Application\Page;
use App\Http\Request;
use App\Session\Users\Login as SessionUsersLogin;

class Login extends Page
{
    /**
     * Método responsável por deslogar o usuário
     * @param Request $request
     * @throws \Exception
     */
    public static function setLogout(Request $request)
    {
        // recupera variáveis de sessão do usuário
        $session=SessionUsersLogin::getDataSession();
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Realizado logout do usuário.');

        // Destroy a sessão de alert
        SessionUsersLogin::logout($session['usuario']['path']);

        // Redireciona o usuário para a pagina de login
        $request->getRouter()->redirect('/login');

    }
}