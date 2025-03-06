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
use App\Model\Entity\WorshipTeamLineup as EntityWorshipTeamLineup;
use App\Model\Entity\SendMessageWhatsAppWorship as EntitySendMessageWhatsAppWorship;
use App\Model\Entity\AcceptedInviteWorship as EntityAcceptedInviteWorship;
use App\Model\Entity\AccessTokenWhatsApp as EntityAccessTokenWhatsApp;
use DateTime;
use DateTimeZone;
use Exception;

class WhatsAppMessageWorshipTeam extends Api
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

        $headerParams = [];

        // Listar escala
        $dateStart = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dateStart = $dateStart->modify('+1 day');
        $dateEnd = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dateEnd = $dateEnd->modify('+1 day');

        $dateStart = $dateStart->format('Y-m-d') . ' 00:00:00';
        $dateEnd = $dateEnd->format('Y-m-d') . ' 23:59:59';

        $where = '`scheduler_date` BETWEEN "' . $dateStart . '" AND "' . $dateEnd . '"';
        $fields = '*';
        $groupBY = 'completed_name';
        $results = EntityWorshipTeamLineup::getWorshipTeamLineup($where,null,null,$groupBY,$fields);
        //Debug::debug($results->fetchAll());

        if($results->rowCount() === 0) throw new Exception("Nenhum registro encontrado.", 404);

        // Processa cada linha da escala
        while ($obWorshipTeamLineup = $results->fetchObject(EntityWorshipTeamLineup::class)) {

            // valida se já enviou notificação
            $schedulerId = $obWorshipTeamLineup->id;
            $obInviteMessageWhatsApp = EntityAcceptedInviteWorship::getSendMessageBySchedulerId($schedulerId);
            if($obInviteMessageWhatsApp instanceof EntityAcceptedInviteWorship){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Agenda já notificada: '. $obInviteMessageWhatsApp->scheduler_date . ' | quem: ' . $obInviteMessageWhatsApp->complete_name . ' | telefone: ' . $obInviteMessageWhatsApp->contato . ' | messageId: ' . $obInviteMessageWhatsApp->message_id, null);
                //throw new Exception('Notificação já enviada: ' . $obInviteMessageWhatsApp->message_id, 422);
                $error = 'Notificação já enviada: ' . $obInviteMessageWhatsApp->message_id;
                $errors[] = $error;
            }else {

                $schedulerDate = new DateTime($obWorshipTeamLineup->scheduler_date);
                $schedulerDate = $schedulerDate->format('d-m-Y');
                $to = '55' . $obWorshipTeamLineup->contato;
                $bodyParams = [
                    [
                        "type" => "text",
                        "text" => $obWorshipTeamLineup->completed_name
                    ],
                    [
                        "type" => "text",
                        "text" => $schedulerDate
                    ],
                    [
                        "type" => "text",
                        "text" => ($obWorshipTeamLineup->day_short_description == 'Sab') ? "08:45" : (($obWorshipTeamLineup->day_short_description == 'Dom') ? "18:45" : "19:45")
                    ],
                    [
                        "type" => "text",
                        "text" => $obWorshipTeamLineup->day_long_description
                    ]
                ];

                $buttonParams = [
                ];

                // Envia a mensagem e acumula a resposta
                $sendResponse = self::send($to, TEMPLATE_SEND_WORSHIP_WHATSAPP, $headerParams, $bodyParams, $buttonParams);
                $response[] = $sendResponse;  // Adiciona a resposta ao array
                if ($sendResponse['messages'][0]['message_status'] == 'accepted') {
                    // cria send message whatsapp
                    $messageId = $sendResponse['messages'][0]['id'];
                    $messageStatus = $sendResponse['messages'][0]['message_status'];
                    $recipientId = $sendResponse['contacts'][0]['input'];
                    $messageTimestamp = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
                    parent::setLog($request, $trace[0]['class'] . '->' . $trace[0]['function'], 'PRIMEIRA: Mensagem Id não encontrado: ' . $messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                    self::setSendMessageWhatsApp($obWorshipTeamLineup->worship_team_id, $to, $messageId, $messageStatus, json_encode($sendResponse));
                    self::setInviteMessageWhatsApp($obWorshipTeamLineup->id, $obWorshipTeamLineup->worship_team_id, $messageId);
                }
            }
        }


        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Notificações recepção enviadas: ' . json_encode($response), null);
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
        $fields = '*';
        $groupBY = 'complete_name';
        $results = EntityAcceptedInviteWorship::getAcceptedInvite($where,null,null,$groupBY,$fields);
        //Debug::debug($results->fetchAll());

        if($results->rowCount() === 0) throw new Exception("Nenhum registro encontrado.", 404);

        // Processa cada linha da escala
        while ($obAcceptedInvite = $results->fetchObject(EntityAcceptedInviteWorship::class)) {

            //recupera

            // valida se já enviou notificação
            $schedulerId = $obAcceptedInvite->id;
            $obInviteMessageWhatsApp = EntityAcceptedInviteWorship::getSendMessageBySchedulerId($schedulerId);
            if($obInviteMessageWhatsApp instanceof EntityAcceptedInviteWorship){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Agenda já notificada: '. $obInviteMessageWhatsApp->scheduler_date . ' | quem: ' . $obInviteMessageWhatsApp->complete_name . ' | telefone: ' . $obInviteMessageWhatsApp->contato . ' | messageId: ' . $obInviteMessageWhatsApp->message_id, null);
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
                    "text" => ($obAcceptedInvite->day_long_description == 'Sábado') ? "08:45" : (($obAcceptedInvite->day_long_description == 'Domingo') ? "18:45" : "19:45")
                ],
                [
                    "type" => "text",
                    "text" => $obAcceptedInvite->day_long_description
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
                self::setSendMessageWhatsApp($obAcceptedInvite->worshipteam_id,$to,$messageId,$messageStatus,json_encode($sendResponse));
                //self::setInviteMessageWhatsApp($obWorshipTeamLineup->id, $obWorshipTeamLineup->sound_team_id,$messageId);
            }
        }


        parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Lembretes escala enviadas: ' . json_encode($response), null);
        return $response;
    }

    /**
     * Método responsável por cadastrar o envio da mensagem
     * @param $teamId
     * @param $phoneNumber
     * @param $messageId
     * @param $messageStatus
     * @param $payload
     * @return void
     * @throws Exception
     */
    private static function setSendMessageWhatsApp($teamId,$phoneNumber,$messageId,$messageStatus,$payload): void
    {
        $obSendMessageWhatsApp = new EntitySendMessageWhatsAppWorship();
        $obSendMessageWhatsApp->worshipteam_id = $teamId;
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
     * @param $teamId
     * @param $messageId
     * @return void
     * @throws Exception
     */
    private static function setInviteMessageWhatsApp($schedulerId,$teamId,$messageId)
    {
        $obInviteMessageWhatsApp = new EntityAcceptedInviteWorship();
        $obInviteMessageWhatsApp->worshipteam_id = $teamId;
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
                return match ($messageStatus) {
                    'accepted', 'sent', 'delivered', 'read' => self::setUpdateMessageWebhook($messageId,$messageStatus, $messageTimestamp,$messageRecipientId, $request),
                    'received' => self::setInvitationAccepted($messageId,$messageStatus,$messageTimestamp,$messageRecipientId,$messageText,$request),
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


        $obSendMessageWhatsApp =  EntitySendMessageWhatsAppWorship::getSendMessageByIdMessage($messageId);
        if(!$obSendMessageWhatsApp instanceof EntitySendMessageWhatsAppWorship){
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
            $obLogs = Logs::getLogs(' `data` LIKE "%'. $messageId . '%"','id DESC', 1,null,' COUNT(id) as total_records');
            //Debug::debug($obLogs);
            $totalRecords = $obLogs->fetchObject(Logs::class)->total_records;
            if($totalRecords === 0){
                parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'ACCEPTED_INVITE: Mensagem Id não encontrado: ' .$messageId . ' | para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
                throw new Exception("Id mensagem não encontrado.",400);
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

        $obSendMessageWhatsApp =  EntitySendMessageWhatsAppWorship::getSendMessageByIdMessage($messageId);
        if(!$obSendMessageWhatsApp instanceof EntitySendMessageWhatsAppWorship){
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
        $receptionteamId = $obSendMessageWhatsApp->worshipteam_id;


        $obSendMessageWhatsApp = new EntitySendMessageWhatsAppWorship();
        if($currentStatus === $messageStatus){
            $obSendMessageWhatsApp->id = $id;
            $obSendMessageWhatsApp->message_status = $messageStatus;
            $obSendMessageWhatsApp->timestamp_message = $messageTimestamp->format('Y-m-d H:i:s');
            $obSendMessageWhatsApp->payload = json_encode($request->getPostVars());
            $obSendMessageWhatsApp->updateStatus();
            //registra log
            parent::setLog($request, $trace[0]['class'] . '->' .  $trace[0]['function'], 'Atualização status da mensagem envia via whatsapp para o número:' . $recipientId . ' | id da mensagem: ' . $messageId . ' | status atual: ' . $currentStatus . ' | status atualizado: ' . $messageStatus . ' | data hora recebimento: ' . $messageTimestamp->format('Y-m-d H:i:s'), null);
        }else{
            $obSendMessageWhatsApp->worshipteam_id = $receptionteamId;
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
