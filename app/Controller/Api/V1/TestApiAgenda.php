<?php

namespace App\Controller\Api\V1;

use App\Http\Request;
use App\Utils\Debug;
use App\Utils\WhatsAppMessageSender;
use Exception;

class TestApiAgenda
{
    /**
     * API teste envio mensagem WhatsApp
     * @param Request $request
     * @return mixed|string
     */
    public static function sendMensage(Request $request): mixed
    {
        try{
            $accessToken = 'EAAHnuOr46sUBOZCK1aZAn2gurFqc0o5BHn45wxDJdm9xFsFSZBonw0XWE8tCSqqPFvvOXNRuBZC41tF7SOxIwXEkrsZC1pRx5LcDat9yR7TuNf9VzzZByRQQKTKnTopzUapgUyG5kGPeZCLbBYYon8zJnPzV3vF8MKths6zZCesR4k3AG0EXZAtZALj2oPGdAA250svbnu3DfaTu4ECdlTdOD6UDf7hj4ZD';
            $sender = new WhatsAppMessageSender($accessToken);

            $postVars = $request->getPostVars();
            //Debug::debug($postVars);
            $to = $postVars['to'];
            $template = $postVars['template'];
            $herderParams = $postVars['herder_params'];
            $bodyParams = $postVars['body_params'];

            return $sender->sendMessage($to, $template, $herderParams, $bodyParams);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}
