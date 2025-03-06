<?php

namespace App\Controller\Api\V1;

use App\Controller\Log\Log as ControllerLog;
use App\Http\Request;
use App\Model\Entity\Organization as EntityOrganization;
use App\Session\Users\Login as SessionUsersLogin;
use App\Utils\Pagination;
use Exception;

class Api
{
    /**
     * Método responsável por retornar os detalhes de uma API
     * @param $request
     * @return array
     * @throws Exception
     */
    public static function getDetails($request): array
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        // Carrega os dados da organização
        $obOrganization = EntityOrganization::getOrganization(null,'created_at DESC',1)->fetchObject();

        //registra log
        self::setLog($request, $trace[0]['class'] . '->' . $trace[0]['function'], 'Retornou detalhes da API', null);

        return [
            'name' => 'API - ' . $obOrganization->full_name,
            'version' => 'v1',
            'author' => $obOrganization->development,
            'email' => 'walter.moura@wmourax.com.br'
        ];
    }

    /**
     * Método responsável por retornar os detalhes da paginação
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    protected static function getPagination($request, $obPagination): array
    {
        // Obter queryParams
        $queryParams = $request->getQueryParams();

        // Páginas
        $pages = $obPagination->getPages();

        // Retorno dados de paginação
        return [
            'currentPage' => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'totalPages' => !empty($pages) ? count($pages): 1
        ];
    }

    /**
     * Método responsável por registrar o log
     * @throws Exception
     */
    public static function setLog(Request $request, string $class, string $data, $token)
    {
        // recupera variáveis de sessão do usuário
        return ControllerLog::setLog($request,-1,$class,$data,$token);
    }
}