<?php

namespace App\Utils;

use Exception;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class WhatsAppMessageSender
{
    private string $access_token;
    private string $api_url;

    /**
     * Instancia a classe
     * @param $access_token
     * @param $phone_number_id
     */
    public function __construct($access_token, $phone_number_id)
    {
        $this->access_token = $access_token;
        $this->api_url = URL_BASE_API_WHATSAPP. '/' . VERSION_API_WHATSAPP .'/'. $phone_number_id .'/messages';
    }

    /**
     * Método responsável por enviar a mensagem
     * @param $to
     * @param $templateName
     * @param $headerParams
     * @param $bodyParams
     * @param $buttonParams
     * @return mixed
     * @throws Exception
     */
    public function sendMessage($to, $templateName, $headerParams, $bodyParams, $buttonParams): mixed
    {
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => [
                    "code" => "pt_BR"
                ],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => $headerParams
                    ],
                    [
                        "type" => "body",
                        "parameters" => $bodyParams
                    ],
//                    [
//                        "type" => "button",
//                        "sub_type" => "url",
//                        "index" => 0,
//                        "parameters" => $buttonParams
//                    ],
//                    [
//                        "type" => "button",
//                        "sub_type" => "url",
//                        "index" => 1,
//                        "parameters" => $buttonParams
//                    ]
                ]
            ]
        ];

        $ch = curl_init($this->api_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }

        return json_decode($response, true);
    }

    /**
     * Método responsável por enviar a mensagem
     * @param $to
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    public function sendMessageText($to, $payload): mixed
    {
        $data = $payload;
        //Debug::debug($data);
        $ch = curl_init($this->api_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception('cURL Error: ' . $error);
        }

        return json_decode($response, true);
    }
}
