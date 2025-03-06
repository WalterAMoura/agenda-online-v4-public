<?php

namespace App\Controller\Login;

use App\Controller\Log\Log as ControllerLog;
use App\Http\Request;
use App\Utils\Pagination;
use App\Utils\View;
use Exception;

class Page
{
    /**
     * Método responsável por retornar o conteúdo da estrutura de páginas do painel
     * @param string $title
     * @param string  $content
     * @return string
     */
    public static function getPage($title,$content)
    {
        return View::render('page', [
            'title' => $title,
            'content' => $content
        ]);
    }

    /**
     * @throws Exception
     */
    public static function setLog(Request $request, string $class, string $data, $token, int $userId)
    {
        //Debug::debug($session);
        //return [ 'success'=> true ];
        return ControllerLog::setLog($request, $userId, $class,$data,$token);
    }
}