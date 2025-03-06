<?php

namespace App\Controller\Api\V1;

use App\Controller\Log\Log;
use App\Http\Request;
use App\Model\Entity\ApiKey as EntityApikey;
use App\Model\Entity\Logs;
use App\Model\Entity\Organization as EntityOrganization;
use App\Utils\Debug;
use App\Utils\JWEDecrypt;
use App\Utils\JWEEncrypt;
use App\Utils\UUID;
use App\Utils\WhatsAppMessageSender;
use App\Model\Entity\SoundTeamLineup as EntitySoundTeamLineup;
use App\Model\Entity\SendMessageWhatsApp as EntitySendMessageWhatsApp;
use App\Model\Entity\SendMessageWhatsAppReception as EntitySendMessageWhatsAppReception;
use App\Model\Entity\SendMessageWhatsAppWorship as EntitySendMessageWhatsAppWorship;
use App\Model\Entity\AcceptedInvite as EntityAcceptedInvite;
use App\Model\Entity\AcceptedInviteReception as EntityAcceptedInviteReception;
use App\Model\Entity\AcceptedInviteWorship as EntityAcceptedInviteWorship;
use App\Model\Entity\ControlAccessToken as EntityControlAccessToken;
use App\Model\Entity\AccessTokenWhatsApp as EntityAccessTokenWhatsApp;
use DateTime;
use DateTimeZone;
use Exception;

class WhatsAppMessageSoundTeam extends Api
{

    /**
     * API teste envio mensagem WhatsApp
     * @param $to
     * @param $template
     * @param $headerParams
     * @param $bodyParams
     * @param $buttonParams
     * @return mixed|string
     * @throws Exception
     */
    private static function send($to, $template, $headerParams, $bodyParams, $buttonParams): mixed
    {
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppByStatusId(3);

        if(!$obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            throw new Exception("Error ao recuperar token do whatsapp ou não existe token cadastrado.", 404);
        }

        try{
            $accessToken = $obAccessTokenWhatsApp->graph_api_token;
            $phoneNumberId= $obAccessTokenWhatsApp->business_phone_number_id;
            $sender = new WhatsAppMessageSender($accessToken, $phoneNumberId);

            return $sender->sendMessage($to, $template, $headerParams, $bodyParams, $buttonParams);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Envia mensagens via WhatsApp para cada linha de escala do time de som e acumula as respostas.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function sendMessage(Request $request): array
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        $response = [];  // Array para acumular as respostas das requisições
        $errors = []; // Array para acumular os errors de envio

        $headerParams = [
            [
                "type" => "text",
                "text" => " - Adventistas Parque Regina"
            ]
        ];

        // Listar escala
        $dateStart = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dateStart = $dateStart->modify('+1 day');
        $dateEnd = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dateEnd = $dateEnd->modify('+1 day');

        $dateStart = $dateStart->format('Y-m-d') . ' 00:00:00';
        $dateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';

        $where = '`scheduler_date` BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';
        $fields = '*, GROUP_CONCAT(device SEPARATOR " e ") as multi_devices ';
        $groupBY = 'completed_name';
        $results = EntitySoundTeamLineup::getSoundTeamLineup($where,null,null,$groupBY,$fields);
        //Debug::debug($results->fetchAll());

        if($results->rowCount() === 0) throw new Exception("Nenhum registro encontrado.", 404);

        // Processa cada linha da escala
        while ($obSoundTeamLineup = $results->fetchObject(EntitySoundTeamLineup::class)) {

            // valida se já enviou notificação
            $schedulerId = $obSoundTeamLineup->id;
            $obInviteMessageWhatsApp = EntityAcceptedInvite::getSendMessageBySchedulerId($schedulerId);
            if($obInviteMessageWhatsApp instanceof EntityAcceptedInvite){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Agenda já notificada: '. $obInviteMessageWhatsApp->scheduler_date . ' | quem: ' . $obInviteMessageWhatsApp->complete_name . ' | telefone: ' . $obInviteMessageWhatsApp->contato . ' | onde: ' . $obInviteMessageWhatsApp->device . ' | messageId: ' . $obInviteMessageWhatsApp->message_id, null);
                //throw new Exception('Notificação já enviada: ' . $obInviteMessageWhatsApp->message_id, 422);
                $error = 'Notificação já enviada: ' . $obInviteMessageWhatsApp->message_id;
                $errors[] = $error;
            }else {

                $schedulerDate = new DateTime($obSoundTeamLineup->scheduler_date);
                $schedulerDate = $schedulerDate->format('d-m-Y');
                $to = '55' . $obSoundTeamLineup->contato;
                $bodyParams = [
                    [
                        "type" => "text",
                        "text" => $obSoundTeamLineup->completed_name
                    ],
                    [
                        "type" => "text",
                        "text" => $schedulerDate
                    ],
                    [
                        "type" => "text",
                        "text" => $obSoundTeamLineup->suggested_time
                    ],
                    [
                        "type" => "text",
                        "text" => $obSoundTeamLineup->day_long_description
                    ],
                    [
                        "type" => "text",
                        "text" => $obSoundTeamLineup->multi_devices
                    ]
                ];

                $buttonParams = [
                ];

                // Envia a mensagem e acumula a resposta
                $sendResponse = self::send($to, TEMPLATE_SEND_AGENDA_WHATSAPP, $headerParams, $bodyParams, $buttonParams);
                $response[] = $sendResponse;  // Adiciona a resposta ao array
                if ($sendResponse['messages'][0]['message_status'] == 'accepted') {
                    // cria send message whatsapp
                    $messageId = $sendResponse['messages'][0]['id'];
                    $messageStatus = $sendResponse['messages'][0]['message_status'];
                    $recipientId = $sendResponse['contacts'][0]['input'];
                    $messageTimestamp = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
                    parent::setLog($request, $trace[0]['class'] . '->' . $trace[0]['function'], 'PRIMEIRA: Mensagem Id não encontrado: ' . $messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                    self::setSendMessageWhatsApp($obSoundTeamLineup->sound_team_id, $to, $messageId, $messageStatus, json_encode($sendResponse));
                    self::setInviteMessageWhatsApp($obSoundTeamLineup->id, $obSoundTeamLineup->sound_team_id, $messageId);
                }
            }
        }


        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Notificações enviadas: ' . json_encode($response), null);
        return [ "success"=> $response,  "failed" => $errors ];
    }

    /**
     * Envia mensagens via WhatsApp para cada linha de escala do time de som e acumula as respostas.
     *
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public static function reminder(Request $request): array
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        $response = [];  // Array para acumular as respostas das requisições

        $headerParams = [
        ];

        // Listar escala
        $dateStart = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        //$dateStart = $dateStart->modify('+1 day');
        $dateEnd = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        //$dateEnd = $dateEnd->modify('+1 day');

        $dateStart = $dateStart->format('Y-m-d') . ' 00:00:00';
        $dateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';

        $where = '`status` IN ("PENDENTE", "ACEITO") AND `scheduler_date` BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';
        $fields = '*, GROUP_CONCAT(device SEPARATOR " e ") as multi_devices ';
        $groupBY = 'complete_name';
        $results = EntityAcceptedInvite::getAcceptedInvite($where,null,null,$groupBY,$fields);
        //Debug::debug($results->fetchAll());

        if($results->rowCount() === 0) throw new Exception("Nenhum registro encontrado.", 404);

        // Processa cada linha da escala
        while ($obAcceptedInvite = $results->fetchObject(EntityAcceptedInvite::class)) {

            //recupera

            // valida se já enviou notificação
            $schedulerId = $obAcceptedInvite->id;
            $obInviteMessageWhatsApp = EntityAcceptedInvite::getSendMessageBySchedulerId($schedulerId);
            if($obInviteMessageWhatsApp instanceof EntityAcceptedInvite){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Agenda já notificada: '. $obInviteMessageWhatsApp->scheduler_date . ' | quem: ' . $obInviteMessageWhatsApp->complete_name . ' | telefone: ' . $obInviteMessageWhatsApp->contato . ' | onde: ' . $obInviteMessageWhatsApp->device . ' | messageId: ' . $obInviteMessageWhatsApp->message_id, null);
                throw new Exception('Notificação já enviada: ' . $obInviteMessageWhatsApp->message_id, 422);
            }

            $schedulerDate = new DateTime($obAcceptedInvite->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');
            $to = '55' . $obAcceptedInvite->contato;
            $bodyParams = [
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->complete_name
                ],
                [
                    "type" => "text",
                    "text" => $schedulerDate
                ],
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->suggested_time
                ],
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->day_long_description
                ],
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->multi_devices
                ]
            ];

            $buttonParams = [
            ];

            // Envia a mensagem e acumula a resposta
            $sendResponse = self::send($to, TEMPLATE_REMINDER_AGENDA_WHATSAPP, $headerParams, $bodyParams, $buttonParams);
            $response[] = $sendResponse;  // Adiciona a resposta ao array
            if($sendResponse['messages'][0]['message_status'] == 'accepted'){
                // cria send message whatsapp
                $messageId = $sendResponse['messages'][0]['id'];
                $messageStatus = $sendResponse['messages'][0]['message_status'];
                $recipientId = $sendResponse['contacts'][0]['input'];
                $messageTimestamp = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Id mensagem: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                self::setSendMessageWhatsApp($obAcceptedInvite->soundteam_id,$to,$messageId,$messageStatus,json_encode($sendResponse));
                //self::setInviteMessageWhatsApp($obSoundTeamLineup->id, $obSoundTeamLineup->sound_team_id,$messageId);
            }
        }


        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Lembretes escala enviadas: ' . json_encode($response), null);
        return $response;
    }

    /**
     * Método responsável por cadastrar o envio da mensagem
     * @param $soundteamId
     * @param $phoneNumber
     * @param $messageId
     * @param $messageStatus
     * @param $payload
     * @return void
     * @throws Exception
     */
    private static function setSendMessageWhatsApp($soundteamId,$phoneNumber,$messageId,$messageStatus,$payload): void
    {
        $obSendMessageWhatsApp = new EntitySendMessageWhatsApp();
        $obSendMessageWhatsApp->soundteam_id = $soundteamId;
        $obSendMessageWhatsApp->message_id = $messageId;
        $obSendMessageWhatsApp->phone_number_sent = $phoneNumber;
        $obSendMessageWhatsApp->message_status = $messageStatus;
        $obSendMessageWhatsApp->payload = $payload;
        $obSendMessageWhatsApp->timestamp_message = null;
        $obSendMessageWhatsApp->cadastrar();
    }

    /**
     * Método responsável por cadastrar o controle de aceite
     * @param $schedulerId
     * @param $soundteamId
     * @param $messageId
     * @return void
     * @throws Exception
     */
    private static function setInviteMessageWhatsApp($schedulerId,$soundteamId,$messageId)
    {
        $obInviteMessageWhatsApp = new EntityAcceptedInvite();
        $obInviteMessageWhatsApp->soundteam_id = $soundteamId;
        $obInviteMessageWhatsApp->message_id = $messageId;;
        $obInviteMessageWhatsApp->status = 'PENDENTE';
        $obInviteMessageWhatsApp->scheduler_id = $schedulerId;
        $obInviteMessageWhatsApp->cadastrar();

    }

    /**
     * Método responsável por enviar uma mensagem de texto
     * @param $to
     * @param $payload
     * @return mixed|string
     * @throws Exception
     */
    private static function sendMessageAcceptedInvite($to, $payload)
    {
        $obAccessTokenWhatsApp = EntityAccessTokenWhatsApp::getAccessTokenWhatsAppByStatusId(3);

        if(!$obAccessTokenWhatsApp instanceof EntityAccessTokenWhatsApp){
            throw new Exception("Error ao recuperar token do whatsapp ou não existe token cadastrado.", 404);
        }
        //Debug::debug($obAccessTokenWhatsApp);
        try{
            $accessToken = $obAccessTokenWhatsApp->graph_api_token;
            $phoneNumberId = $obAccessTokenWhatsApp->business_phone_number_id;
            $sender = new WhatsAppMessageSender($accessToken,$phoneNumberId);

            return $sender->sendMessageText($to,$payload);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Webhook WhatsApp
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function webhook(Request $request): mixed
    {
        $path = $request->getRouter()->getPathMain();
        //Debug::debug($request->getPostVars());
        //Debug::debug($request->getQueryParams());
        $queryParams = $request->getQueryParams();
        $postVars = $request->getPostVars();

        $mode = $queryParams['hub_mode']??"";

        switch ($mode) {
            case 'subscribe':
                $challenge = $queryParams['hub_challenge'];
                $verifyToken = $queryParams['hub_verify_token'];
               return self::healthCheckWhatsApp($path,$verifyToken,$challenge,$request);
            default:
                //Debug::debug($postVars);
                $messageId = "";
                $messageStatus = "";
                $messageTimestamp = "";
                $messageRecipientId = "";
                $messageText = "";
                $entry = $postVars['entry'];

                foreach ($entry as $entries) {
                    foreach ($entries['changes'] as $change) {
                        foreach ($change['value']['statuses'] as $statuses) {
                            $messageId = $statuses['id'];
                            $messageStatus = $statuses['status'];
                            $messageTimestamp = $statuses['timestamp'];
                            $messageRecipientId = $statuses['recipient_id'];
                        }
                        foreach ($change['value']['messages'] as $message) {
                            $messageId = $message['context']['id'];
                            $messageStatus = "received";
                            $messageTimestamp = $message['timestamp'];
                            $messageRecipientId = $message['from'];
                            $messageText = $message['button']['text']??$message['text']['body'];
                            //$messageText = $message['text']['body'];
                            //$messageText = json_encode($message);
                        }
                   }
                }
                $obSendMessageWhatsApp =  EntitySendMessageWhatsApp::getSendMessageByIdMessage($messageId);
                $obSendMessageWhatsAppWorship = EntitySendMessageWhatsAppWorship::getSendMessageByIdMessage($messageId);

                return match ($messageStatus) {
                    'accepted', 'sent', 'delivered', 'read' => self::setUpdateMessageWebhook($messageId,$messageStatus, $messageTimestamp,$messageRecipientId, $request),
                    'received' => (($obSendMessageWhatsApp instanceof EntitySendMessageWhatsApp)? self::setInvitationAccepted($messageId,$messageStatus,$messageTimestamp,$messageRecipientId,$messageText,$request) : ($obSendMessageWhatsAppWorship instanceof EntitySendMessageWhatsAppWorship))?  self::setInvitationAcceptedWorship($messageId, $messageStatus, $messageTimestamp, $messageRecipientId,$messageText, $request): self::setInvitationAcceptedReception($messageId,$messageStatus,$messageTimestamp,$messageRecipientId,$messageText,$request),
                    default => throw new Exception("Status não esperado.",400)
                };
        }
    }

    /**
     * Método responsável por atualizar o status do invite
     * @param $messageId
     * @param $messageStatus
     * @param $messageTimestamp
     * @param $recipientId
     * @param $messageText
     * @param Request $request
     * @return array|void
     * @throws Exception
     */
    private static function setInvitationAccepted($messageId, $messageStatus,$messageTimestamp, $recipientId, $messageText, Request $request)
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        // verifica se existe o id da mensagem na tabela de controle de envios de mensagens
        $messageTimestamp =  new DateTime("@$messageTimestamp", new DateTimeZone('America/Sao_Paulo'));

        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'RESPOSTA_RECEBIDA: '. json_encode($request->getPostVars()) .' | Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);



        $obSendMessageWhatsApp =  EntitySendMessageWhatsApp::getSendMessageByIdMessage($messageId);
        if(!$obSendMessageWhatsApp instanceof EntitySendMessageWhatsApp){
            $obLogs = Logs::getLogs(' `data` LIKE "%'. $messageId . '%"','id DESC', 1,null,' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if($totalRecords === 0){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'PRIMEIRA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.",400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'SEGUNDA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];
        }

        // verifica se o id da mensagem existe no controle de aceite
        $obAcceptedInvite = EntityAcceptedInvite::getSendMessageByIdMessage($messageId);
        if(!$obAcceptedInvite instanceof EntityAcceptedInvite){
            $obLogs = Logs::getLogs(' `data` LIKE "%' . $messageId . '%"', 'id DESC', 1, null, ' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if ($totalRecords === 0) {
                parent::setLog($request, $trace[0]['class'] . '->' . $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' . $messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.", 400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];

        }

        $id = $obAcceptedInvite->id;
        //$schedulerDate = $obAcceptedInvite->scheduler_date;

        if(strtolower($messageText) === 'confirme sua presença' or $messageText === '1') {
            $obAcceptedInvite = new EntityAcceptedInvite();
            $obAcceptedInvite->id = $id;
            $obAcceptedInvite->status = 'ACEITO';
            $obAcceptedInvite->timestamp_accepted = $messageTimestamp->format('Y-m-d H:i:s');
            $obAcceptedInvite->updateStatus();

            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $recipientId,
                "type" => "text",
                "text" => [
                    "preview_url" => true,
                    "body" => "Agenda confirmada com sucesso, Deus Abençoe!!!"
                ]
            ];
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'CONFIRMOU: Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }elseif (strtolower($messageText) === 'não poderei estar' or $messageText === '2'){
            $obAcceptedInvite = new EntityAcceptedInvite();
            $obAcceptedInvite->id = $id;
            $obAcceptedInvite->status = 'REJEITADO';
            $obAcceptedInvite->timestamp_accepted = $messageTimestamp->format('Y-m-d H:i:s');
            $obAcceptedInvite->updateStatus();

            $obAcceptedInvite = EntityAcceptedInvite::getSendMessageById($id);
            //Debug::debug($obAcceptedInvite);
            $to = $recipientId;
            $schedulerDate = new DateTime($obAcceptedInvite->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $headerParams = [
            ];

            $bodyParams = [
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->complete_name
                ],
                [
                    "type" => "text",
                    "text" => $schedulerDate
                ],
                [
                    "type" => "text",
                    "text" =>  $schedulerDate
                ]
            ];

            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'NAO_CONFIRMOU: Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            // Envia a mensagem e acumula a resposta
            return self::send($to, TEMPLATE_SEND_MODIFY_AGENDA_WHATSAPP, $headerParams, $bodyParams, []);

        }else{
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $recipientId,
                "type" => "text",
                "text" => [
                    "preview_url"=> true,
                    "body"=> "Desculpe não entendemos sua mensagem, entre em contato com o administrador do sistema."
                ]
            ];
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'RESPOSTA_INESPERADA: '. $messageText .' | Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }
        return self::sendMessageAcceptedInvite($recipientId,$payload);
    }

    /**
     * Método responsável por atualizar o status do invite
     * @param $messageId
     * @param $messageStatus
     * @param $messageTimestamp
     * @param $recipientId
     * @param $messageText
     * @param Request $request
     * @return array|void
     * @throws Exception
     */
    private static function setInvitationAcceptedReception($messageId, $messageStatus,$messageTimestamp, $recipientId, $messageText, Request $request)
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        // verifica se existe o id da mensagem na tabela de controle de envios de mensagens
        $messageTimestamp =  new DateTime("@$messageTimestamp", new DateTimeZone('America/Sao_Paulo'));

        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'RESPOSTA_RECEBIDA: '. json_encode($request->getPostVars()) .' | Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);


        $obSendMessageWhatsAppReception =  EntitySendMessageWhatsAppReception::getSendMessageByIdMessage($messageId);
        if(!$obSendMessageWhatsAppReception instanceof EntitySendMessageWhatsAppReception){
            $obLogs = Logs::getLogs(' `data` LIKE "%'. $messageId . '%"','id DESC', 1,null,' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if($totalRecords === 0){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'PRIMEIRA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.",400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'SEGUNDA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];
        }

        // verifica se o id da mensagem existe no controle de aceite
        $obAcceptedInvite = EntityAcceptedInviteReception::getSendMessageByIdMessage($messageId);
        if(!$obAcceptedInvite instanceof EntityAcceptedInviteReception){
            $obLogs = Logs::getLogs(' `data` LIKE "%' . $messageId . '%"', 'id DESC', 1, null, ' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if ($totalRecords === 0) {
                parent::setLog($request, $trace[0]['class'] . '->' . $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' . $messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.", 400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];

        }

        $id = $obAcceptedInvite->id;
        //$schedulerDate = $obAcceptedInvite->scheduler_date;

        if(strtolower($messageText) === 'confirme sua presença' or $messageText === '1') {
            $obAcceptedInvite = new EntityAcceptedInviteReception();
            $obAcceptedInvite->id = $id;
            $obAcceptedInvite->status = 'ACEITO';
            $obAcceptedInvite->timestamp_accepted = $messageTimestamp->format('Y-m-d H:i:s');
            $obAcceptedInvite->updateStatus();

            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $recipientId,
                "type" => "text",
                "text" => [
                    "preview_url" => true,
                    "body" => "Agenda confirmada com sucesso, Deus Abençoe!!!"
                ]
            ];
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'CONFIRMOU: Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }elseif (strtolower($messageText) === 'não poderei estar' or $messageText === '2'){
            $obAcceptedInvite = new EntityAcceptedInviteReception();
            $obAcceptedInvite->id = $id;
            $obAcceptedInvite->status = 'REJEITADO';
            $obAcceptedInvite->timestamp_accepted = $messageTimestamp->format('Y-m-d H:i:s');
            $obAcceptedInvite->updateStatus();

            $obAcceptedInvite = EntityAcceptedInviteReception::getSendMessageById($id);
            //Debug::debug($obAcceptedInvite);
            $to = $recipientId;
            $schedulerDate = new DateTime($obAcceptedInvite->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $headerParams = [
            ];

            $bodyParams = [
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->complete_name
                ],
                [
                    "type" => "text",
                    "text" => $schedulerDate
                ],
                [
                    "type" => "text",
                    "text" =>  $schedulerDate
                ]
            ];

            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'NAO_CONFIRMOU: Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            // Envia a mensagem e acumula a resposta
            return self::send($to, TEMPLATE_SEND_MODIFY_AGENDA_WHATSAPP, $headerParams, $bodyParams, []);

        }else{
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $recipientId,
                "type" => "text",
                "text" => [
                    "preview_url"=> true,
                    "body"=> "Desculpe não entendemos sua mensagem, entre em contato com o administrador do sistema."
                ]
            ];
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'RESPOSTA_INESPERADA: '. $messageText .' | Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }
        return self::sendMessageAcceptedInvite($recipientId,$payload);
    }

    /**
     * Método responsável por atualizar o status do invite
     * @param $messageId
     * @param $messageStatus
     * @param $messageTimestamp
     * @param $recipientId
     * @param $messageText
     * @param Request $request
     * @return array|void
     * @throws Exception
     */
    private static function setInvitationAcceptedWorship($messageId, $messageStatus,$messageTimestamp, $recipientId, $messageText, Request $request)
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        // verifica se existe o id da mensagem na tabela de controle de envios de mensagens
        $messageTimestamp =  new DateTime("@$messageTimestamp", new DateTimeZone('America/Sao_Paulo'));

        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'RESPOSTA_RECEBIDA: '. json_encode($request->getPostVars()) .' | Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);


        $obSendMessageWhatsAppWorship =  EntitySendMessageWhatsAppWorship::getSendMessageByIdMessage($messageId);
        if(!$obSendMessageWhatsAppWorship instanceof EntitySendMessageWhatsAppWorship){
            $obLogs = Logs::getLogs(' `data` LIKE "%'. $messageId . '%"','id DESC', 1,null,' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if($totalRecords === 0){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'PRIMEIRA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.",400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'SEGUNDA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];
        }

        // verifica se o id da mensagem existe no controle de aceite
        $obAcceptedInvite = EntityAcceptedInviteWorship::getSendMessageByIdMessage($messageId);
        if(!$obAcceptedInvite instanceof EntityAcceptedInviteWorship){
            $obLogs = Logs::getLogs(' `data` LIKE "%' . $messageId . '%"', 'id DESC', 1, null, ' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if ($totalRecords === 0) {
                parent::setLog($request, $trace[0]['class'] . '->' . $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' . $messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.", 400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];

        }

        $id = $obAcceptedInvite->id;
        //$schedulerDate = $obAcceptedInvite->scheduler_date;

        if(strtolower($messageText) === 'confirme sua presença' or $messageText === '1') {
            $obAcceptedInvite = new EntityAcceptedInviteWorship();
            $obAcceptedInvite->id = $id;
            $obAcceptedInvite->status = 'ACEITO';
            $obAcceptedInvite->timestamp_accepted = $messageTimestamp->format('Y-m-d H:i:s');
            $obAcceptedInvite->updateStatus();

            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $recipientId,
                "type" => "text",
                "text" => [
                    "preview_url" => true,
                    "body" => "Agenda confirmada com sucesso, Deus Abençoe!!!"
                ]
            ];
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'CONFIRMOU: Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }elseif (strtolower($messageText) === 'não poderei estar' or $messageText === '2'){
            $obAcceptedInvite = new EntityAcceptedInviteWorship();
            $obAcceptedInvite->id = $id;
            $obAcceptedInvite->status = 'REJEITADO';
            $obAcceptedInvite->timestamp_accepted = $messageTimestamp->format('Y-m-d H:i:s');
            $obAcceptedInvite->updateStatus();

            $obAcceptedInvite = EntityAcceptedInviteWorship::getSendMessageById($id);
            //Debug::debug($obAcceptedInvite);
            $to = $recipientId;
            $schedulerDate = new DateTime($obAcceptedInvite->scheduler_date);
            $schedulerDate = $schedulerDate->format('d-m-Y');

            $headerParams = [
            ];

            $bodyParams = [
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->complete_name
                ],
                [
                    "type" => "text",
                    "text" => $schedulerDate
                ],
                [
                    "type" => "text",
                    "text" =>  $schedulerDate
                ]
            ];

            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'NAO_CONFIRMOU: Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            // Envia a mensagem e acumula a resposta
            return self::send($to, TEMPLATE_SEND_MODIFY_AGENDA_WHATSAPP, $headerParams, $bodyParams, []);

        }else{
            $payload = [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $recipientId,
                "type" => "text",
                "text" => [
                    "preview_url"=> true,
                    "body"=> "Desculpe não entendemos sua mensagem, entre em contato com o administrador do sistema."
                ]
            ];
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'RESPOSTA_INESPERADA: '. $messageText .' | Mensagem Id: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }
        return self::sendMessageAcceptedInvite($recipientId,$payload);
    }

    /**
     * Método responsável por atualizar o status da mensagem no banco de dados
     * @param $messageId
     * @param $messageStatus
     * @param $messageTimestamp
     * @param $recipientId
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private static function setUpdateMessageWebhook($messageId, $messageStatus,$messageTimestamp, $recipientId, Request $request): array
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        $messageTimestamp =  new DateTime("@$messageTimestamp", new DateTimeZone('America/Sao_Paulo'));

        $obSendMessageWhatsApp =  EntitySendMessageWhatsApp::getSendMessageByIdMessage($messageId);
        if(!$obSendMessageWhatsApp instanceof EntitySendMessageWhatsApp){
            $obSendMessageWhatsAppReception = EntitySendMessageWhatsAppReception::getSendMessageByIdMessage($messageId);
            //Debug::debug($obSendMessageWhatsAppReception);
            if($obSendMessageWhatsAppReception instanceof EntitySendMessageWhatsAppReception){
               return self::setUpdateMessageWebhookWhatsAppReception($messageId,$messageStatus,$messageTimestamp,$recipientId,$request);
            }

            $obSendMessageWhatsAppWorship = EntitySendMessageWhatsAppWorship::getSendMessageByIdMessage($messageId);
            if($obSendMessageWhatsAppWorship instanceof EntitySendMessageWhatsAppWorship){
                return self::setUpdateMessageWebhookWhatsAppWorship($messageId,$messageStatus,$messageTimestamp,$recipientId,$request);
            }

            $obLogs = Logs::getLogs(' `data` LIKE "%'. $messageId . '%"','id DESC', 1,null,' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if($totalRecords === 0){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'PRIMEIRA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.",400);
            }
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'SEGUNDA: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);

            return [];
        }

        $id = $obSendMessageWhatsApp->id;
        $currentStatus = $obSendMessageWhatsApp->message_status;
        $soundteamId = $obSendMessageWhatsApp->soundteam_id;


        $obSendMessageWhatsApp = new EntitySendMessageWhatsApp();
        if($currentStatus === $messageStatus){
            $obSendMessageWhatsApp->id = $id;
            $obSendMessageWhatsApp->message_status = $messageStatus;
            $obSendMessageWhatsApp->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsApp->payload = json_encode($request->getPostVars());
            $obSendMessageWhatsApp->updateStatus();
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Atualização status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atual: ' . $currentStatus . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }else{
            $obSendMessageWhatsApp->soundteam_id = $soundteamId;
            $obSendMessageWhatsApp->phone_number_sent = $recipientId;
            $obSendMessageWhatsApp->message_id = $messageId;
            $obSendMessageWhatsApp->message_status = $messageStatus;
            $obSendMessageWhatsApp->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsApp->payload =  json_encode($request->getPostVars());
            $obSendMessageWhatsApp->cadastrar();

            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Cadastro novo status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }
        return [];

    }

    /**
     * @param $messageId
     * @param $messageStatus
     * @param $messageTimestamp
     * @param $recipientId
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private static function setUpdateMessageWebhookWhatsAppReception($messageId, $messageStatus,$messageTimestamp, $recipientId, Request $request): array
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        //$messageTimestamp =  new DateTime("@$messageTimestamp", new DateTimeZone('America/Sao_Paulo'));

        $obSendMessageWhatsAppReception = EntitySendMessageWhatsAppReception::getSendMessageByIdMessage($messageId);
        $id = $obSendMessageWhatsAppReception->id;
        $currentStatus = $obSendMessageWhatsAppReception->message_status;
        $receptionteamId = $obSendMessageWhatsAppReception->receptionteam_id;

        //Debug::debug($obSendMessageWhatsAppReception);

        $obSendMessageWhatsAppReception = new EntitySendMessageWhatsAppReception();
        if($currentStatus === $messageStatus){
            $obSendMessageWhatsAppReception->id = $id;
            $obSendMessageWhatsAppReception->message_status = $messageStatus;
            $obSendMessageWhatsAppReception->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsAppReception->payload = json_encode($request->getPostVars());
            $obSendMessageWhatsAppReception->updateStatus();
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Atualização status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atual: ' . $currentStatus . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }else{
            $obSendMessageWhatsAppReception->receptionteam_id = $receptionteamId;
            $obSendMessageWhatsAppReception->phone_number_sent = $recipientId;
            $obSendMessageWhatsAppReception->message_id = $messageId;
            $obSendMessageWhatsAppReception->message_status = $messageStatus;
            $obSendMessageWhatsAppReception->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsAppReception->payload =  json_encode($request->getPostVars());
            $obSendMessageWhatsAppReception->cadastrar();

            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Cadastro novo status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }
        return [];
    }

    /**
     * @param $messageId
     * @param $messageStatus
     * @param $messageTimestamp
     * @param $recipientId
     * @param Request $request
     * @return array
     * @throws Exception
     */
    private static function setUpdateMessageWebhookWhatsAppWorship($messageId, $messageStatus,$messageTimestamp, $recipientId, Request $request): array
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        //$messageTimestamp =  new DateTime("@$messageTimestamp", new DateTimeZone('America/Sao_Paulo'));

        $obSendMessageWhatsAppWorship = EntitySendMessageWhatsAppWorship::getSendMessageByIdMessage($messageId);
        $id = $obSendMessageWhatsAppWorship->id;
        $currentStatus = $obSendMessageWhatsAppWorship->message_status;
        $receptionteamId = $obSendMessageWhatsAppWorship->worshipteam_id;

        //Debug::debug($obSendMessageWhatsAppWorship);

        $obSendMessageWhatsAppWorship = new EntitySendMessageWhatsAppWorship();
        if($currentStatus === $messageStatus){
            $obSendMessageWhatsAppWorship->id = $id;
            $obSendMessageWhatsAppWorship->message_status = $messageStatus;
            $obSendMessageWhatsAppWorship->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsAppWorship->payload = json_encode($request->getPostVars());
            $obSendMessageWhatsAppWorship->updateStatus();
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Atualização status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atual: ' . $currentStatus . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }else{
            $obSendMessageWhatsAppWorship->worshipteam_id = $receptionteamId;
            $obSendMessageWhatsAppWorship->phone_number_sent = $recipientId;
            $obSendMessageWhatsAppWorship->message_id = $messageId;
            $obSendMessageWhatsAppWorship->message_status = $messageStatus;
            $obSendMessageWhatsAppWorship->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsAppWorship->payload =  json_encode($request->getPostVars());
            $obSendMessageWhatsAppWorship->cadastrar();

            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Cadastro novo status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }
        return [];
    }

    /**
     * Valida o webhook
     * @param $path
     * @param $verifyToken
     * @param $challenge
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    private static function healthCheckWhatsApp($path,$verifyToken,$challenge, Request $request): mixed
    {
        // Obtendo informações sobre a pilha de chamadas
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        // valida se existe a apikey informada para este path
        $obApikey = EntityApikey::getApikeyByKeyByPath($verifyToken,$path);
        // valida se está apikey tem permissão para acessar
        if($obApikey->status_id !== 1){
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Erro ao verificar webhook, chave não encontrada:' . $verifyToken, null);
            throw new Exception("Invalid authentication credentials.",401);
        }
        //registra log
        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Chave verificada com sucesso:' . $verifyToken . ' | challenge: ' . $challenge, null);

        return $challenge;
    }

}
