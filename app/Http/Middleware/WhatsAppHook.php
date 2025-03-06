<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class WhatsAppHook
{
    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Altera o content type para json
        $request->getRouter()->setContentType('text/html');

        //Executa o próximo nivel do middleware
        return $next($request);

    }
}